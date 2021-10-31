<?php

namespace Eduka\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ThankYouForPurchasing extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $email;
    public $receipt;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $receipt, $token)
    {
        $this->email = $email;
        $this->receipt = $receipt;
        $this->token = $token;
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
         ->subject('Thank you for purchasing Mastering Nova!')
         ->with('preheader', 'Thank you for purchasing Mastering Nova!')
         ->with('button', ['text' => 'Click here to reset your password', 'link' => url("/password/reset/{$this->token}?email={$this->email}")])
         //->with('hero', 'thank-you.jpg')
         ->with('paragraphs', [

             ['Thank you for buying Mastering Nova' => [
                 'Hi there!',
                 'Super excited that you bought my course, Mastering Nova! Thank you very much for it, and I hope you can enjoy it as much as I did recording it!',
                 'Feel free to reach me with issues, improvements or any feedback you want to give. I answer all emails.',
             ],
             ],
             ['Next Step!' => [
                 'You need to reset your password. Please click on the button below and then login.',
             ],
             ],
             ['Where is my invoice?' => [
                 "You can access your invoice inside the dashboard after you log in, or just on this <a href='".$this->receipt."' target='_blank'>link to download it immediately</a>.",
             ],
             ],

         ]);
    }
}
