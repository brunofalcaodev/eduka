<?php

namespace Eduka\Listeners\Paddle;

use Eduka\Models\PaddleLog;
use Eduka\Models\User;
use Illuminate\Http\Request;
use ProtoneMedia\LaravelPaddle\Events\PaymentSucceeded as EventPaymentSucceeded;

class PaymentSucceeded
{
    public $request;

    /**
     * Create the event listener.
     *
     * @param  Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(EventPaymentSucceeded $event)
    {
        $checkout = $event->all();
        // Record the mode. mode:half|full,...
        $segments = explode(',', $checkout['passthrough']);
        $parts = [];

        foreach ($segments as $segment) {
            $value = explode(':', $segment);
            $parts[$value[0]] = $value[1];
        }

        $checkout['mode'] = $parts['mode'];
        $checkout['ip'] = $parts['ip'];

        // Record the checkout data.
        $paddleLog = PaddleLog::storeCheckout($checkout);

        /*
        activity()
            ->performedOn($paddleLog)
            ->withProperties(['checkout_data' => $checkout])
            ->log('Checkout data successfully created');
        */

        // Create user from purchase.
        $user = User::createFromPurchase($paddleLog);

        /*
        activity()
            ->performedOn($user)
            ->withProperties(
                ['checkout_data' => $checkout,
                 'user_data' => $user->get(), ]
            )
            ->log('Paid user successfully created - '.$user->name.', '.$user->email);
        */
    }
}
