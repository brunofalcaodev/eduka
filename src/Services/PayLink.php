<?php

namespace Eduka\Services;

use Eduka\Affiliate;
use ProtoneMedia\LaravelPaddle\Paddle;

class PayLink
{
    protected $payLink;

    public static function make()
    {
        return new self();
    }

    protected function inSession()
    {
        return filled(session('website-paylink')) && env('PADDLE_CHECKOUT_SESSION') == 1;
    }

    protected function fromSession()
    {
        return session('website-paylink');
    }

    protected function toSession()
    {
        session(['website-paylink' => $this]);
    }

    public function link($price, $passthrough = '')
    {
        if ($this->inSession()) {
            $this->payLink = $this->fromSession()->payLink;

            return $this->payLink;
        }

        $ip = request()->ip() ?? '0.0.0.0';

        $this->payLink = Paddle::product()
            ->generatePayLink()
            ->productId(env('PADDLE_PRODUCT_ID'))
            ->returnUrl(secure_url('/paddle/thanks/{checkout_hash}'))
            ->quantityVariable(0)
            ->quantity(1)
            ->prices(["USD:{$price}"]);

        if (session('referer') != null) {
            // Check if we have an affiliate as that referer.
            $affiliate = Affiliate::firstWhere('domain', session('referer'));

            if ($affiliate) {
                $this->payLink->affiliates([$affiliate->paddle_vendor_id.':'.round($affiliate->commission / 100, 2)]);
                $this->payLink->passthrough("ip:{$ip},affiliate:{$affiliate->domain},{$passthrough}");

                activity()
                    ->performedOn($affiliate)
                    ->withProperties(['ip' => request()->ip() ?? '0.0.0.0'])
                    ->log("Affiliate {$affiliate->domain} detected. Applying {$affiliate->commission}% commission on Pay Link");
            }
        } else {
            $this->payLink->passthrough("ip:{$ip},{$passthrough}");
        }

        $this->payLink = $this->payLink->send();

        // Store paylink into session.
        $this->toSession();

        return $this->payLink;
    }
}
