<?php

namespace Eduka\Mail;

use Eduka\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetYourPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $token)
    {
        $this->link = url(route('password.reset', [
                              'token' => $token,
                              'email' => $user->email,
                        ], false));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return
        $this->from(
            'bruno@masteringnova.com',
            'Bruno from Mastering Nova Course'
        )->view('emails.base')
         ->subject('Mastering Nova - Reset password requested')
         ->with('preheader', 'Mastering Nova - Reset password requested')
         ->with('button', ['text' => 'Reset my Password', 'link' => $this->link])
         ->with('paragraphs', [

            ['Password Request requested' => [
                'Hi there,',
                'Looks like you have requested to reset your password.',
                'No worries. We all have our moments of forgetfulness :)',
                'Just click in the button below to be redirected to your password reset page.',
                ],
            ],

        ]);
    }
}
