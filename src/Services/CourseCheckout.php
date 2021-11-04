<?php

namespace Eduka\Services;

use Eduka\Models\Country;
use Eduka\Utils\Price;
use Illuminate\Support\Facades\Request;
use ProtoneMedia\LaravelPaddle\Paddle;

class CourseCheckout
{
    protected $price;

    public static $pricePayload;

    public function __construct()
    {
        $this->getPrice();
    }

    public static function make()
    {
        $checkout = new CourseCheckout();

        return $checkout;
    }

    protected function inSession()
    {
        return filled(session('course-checkout')) && env('PADDLE_CHECKOUT_SESSION') == 1;
    }

    protected function fromSession()
    {
        return session('course-checkout');
    }

    protected function toSession()
    {
        session(['course-checkout' => $this]);
    }

    /**
     * Makes a Paddle api call.
     *
     * @param  string $type
     *
     * @return mixed
     */
    protected function callPaddleApi(string $type)
    {
        try {
            switch ($type) {
                case 'prices':
                    return (object) (Paddle::checkout()
                            ->getPrices([
                                'product_ids' => env('PADDLE_PRODUCT_ID'),
                                'customer_ip' => request()->ip2(), ])
                            ->send());

                break;
            }
        } catch (\Exception $e) {
            // Log error as application log.
            activity()
                ->withProperties(
                    ['ip' => request()->ip2()]
                )
                ->log('ERROR:: Paddle API error - '.$e->getMessage().' in '.$e->getFile().' at line '.$e->getLine());

            return;
        }
    }

    /**
     * Resolves a Paddle api result into a price customized object.
     *
     * @param  mixed $price
     *
     * @return \StdClass
     */
    protected function resolvePrice($payload)
    {
        $result = [];
        data_set($result, 'request.ip', request()->ip2());

        data_set($result, 'request.country.code', $payload->customer_country);
        data_set($result, 'request.country.name', Country::firstWhere('code', $payload->customer_country)->name);
        data_set($result, 'currency.name', $payload->products[0]['currency']);

        /*
         * The amount.default is the default payable amount that Paddle returns.
         * As example, if Paddle is applying a discount in the original price, then
         * this will be that amount. It is also used to calculate the PPP if needed.
         */
        data_set($result, 'amount.default', $payload->products[0]['price']['net']);

        /*
         * The amount.default.half is half of the default amount. It is used
         * if the buyer wants to pay in 2 times (rounded to the minimum).
         */
        data_set($result, 'amount.half', floor($payload->products[0]['price']['net'] / 2));

        /*
         * The amount.original is the real payable amount from Paddle without any
         * discount. It's the original amount above all, without any discount or
         * coupon.
         */
        data_set($result, 'amount.original', $payload->products[0]['list_price']['net']);

        /*
         * Discount computation. If the original is different from the default
         * then a discount is being applied.
         * The number is in percentage. Meaning, if it's 40, means a 40% discount.
         */
        if ($result['amount']['default'] != $result['amount']['original']) {
            data_set($result, 'amount.discount', 100 - $result['amount']['default'] / $result['amount']['original'] * 100);
        }

        data_set($result, 'currency.name', 'USD');
        data_set($result, 'currency.symbol', '$');

        return json_decode(json_encode($result));
    }

    /**
     * Obtains the product price information from Paddle (or a default).
     * Converts price using PPP.
     * Updates $this->price with all the information.
     *
     * @return void
     */
    protected function getProductPrice()
    {
        /**
         * Obtain a price object given the api product price result
         * or a default value from the website.
         */
        $result = $this->callPaddleApi('prices');
        $this->price = $this->resolvePrice($result);

        // Use price data for global purposes.
        static::$pricePayload = $this->price;

        /**
         * Compute the PPP in case a country exists.
         * Get country.
         * Get ppp_index (default is 1).
         * Convert price via ppp_index.
         */
        $country = Country::where('code', $this->price->request->country->code)
                          ->firstOr(function () {
                              $country = new Country();
                              $country->ppp_index = 1;

                              return $country;
                          });

        if (! $this->usePPP() && env('AUTO_PPP') == 0) {
            $country->ppp_index = 1;
        }

        $this->price->amount->checkout = (int) ceil($country->ppp_index * $this->price->amount->default);
        $this->price->amount->original = $this->price->amount->original;
        $this->price->amount->half = $this->price->amount->half;

        $this->price->discount = new \StdClass();
        $this->price->discount->percentage = (int) round(100 - ceil($country->ppp_index * 100));
        $this->price->discount->amount = (int) ($this->price->amount->default -
                                         $this->price->amount->checkout);

        $this->session = false;

        return $this->price;
    }

    protected function usePPP()
    {
        return Request::input('ppp');
    }

    protected function getPrice()
    {
        if ($this->inSession()) {
            // Use session pricing.
            $this->price = $this->fromSession()->price;
            // Use price data for global purposes.
            static::$pricePayload = $this->price;
            $this->session = true;

            return $this->price;
        } else {
            // Let's go to paddle and get the current price information.
            $this->getProductPrice();
        }

        $this->toSession();
    }

    public function allowOptionsFromThisCountry()
    {
        /**
         * For now I just want to make the 2 times payment
         * in countries that are considered 1st 'world'.
         *
         * And these ones for now are for piloting the feature.
         *
         * Later I can expand depending if people are respecting the
         * payments or not.
         */
        $availableCountries = [
            'United States',
            'Germany',
            'Portugal',
            'Spain',
            'France',
            'Switzerland',
            'Italy',
            'Japan',
            'Canada',
            'Australia',
        ];

        return in_array(static::$pricePayload->request->country->name, $availableCountries);
    }

    public function payLink()
    {
        $price = $this->price->amount->checkout;
        $link = PayLink::make()->link($price, 'mode:full');

        return $link['url'];
    }

    public function payLinkHalf()
    {
        $price = $this->price->amount->half;
        $link = PayLink::make()->link($price, 'mode:half');

        /*
         * To avoid back navigations to resubmit the page as a full payment
         * we need to clean the session.
         */
        session()->forget('course-checkout');
        session()->forget('course-paylink');

        return $link['url'];
    }

    public function price()
    {
        return $this->price;
    }
}
