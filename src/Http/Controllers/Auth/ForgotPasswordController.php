<?php

namespace Eduka\Http\Controllers\Auth;

use Eduka\Http\Controllers\Controller;
use Eduka\Mail\ResetPasswordMail;
use Eduka\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Spatie\Honeypot\ProtectAgainstSpam;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(ProtectAgainstSpam::class)->only('sendResetLinkEmail');
    }

    public function test(Request $request)
    {
        return new ResetPasswordMail(User::firstWhere('id', 2), '734td4g376');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        // No matter what response comes, it will always say "okay" to the frontend.
        return back()->with(
            'status',
            'Please check your inbox (and spam folder if needed)<br/> A reset password link was sent to you'
        );
    }
}
