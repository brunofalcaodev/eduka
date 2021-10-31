<?php

namespace Eduka\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LaunchDateAnnouncement extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
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
         ->subject('Mastering Nova was launched!')
         ->with('preheader', 'Mastering Nova was launched!')
         ->with('button', ['text' => 'Go to Mastering Nova Course', 'link' => 'https://www.masteringnova.com'])
         ->with('paragraphs', [

            ['Course Launched!' => [
                'Finally the good news!',
                'After several months recording the best-hidden gems of Laravel Nova the <b>Course is finally out!</b>',
                'Nova is finally demystified. You will learn from zero to advanced techniques that will save you hundreds of hours if you need to learn them all by yourself!',
                'You will learn:',
                '* Complete walkthrough for each of the Nova Resource features',
                '* Undocumented Gems and special features that will speed up your Resources development',
                '* Advanced Resource management, best practices, and deep dives in each of the Nova functionalities',
                'I have launched it with a <b>40% early-access discount with access to 5+ hours of content along 30 videos</b>! The remaining ones will be released until November.',
                ],
            ],

            ['Special discount for you' => [
                'Since you trusted me along with your interest in Mastering Nova, Laraflash, or Laraning, I am giving you a 10% extra coupon that you can use. When you buy your Course purchase please <b>enter the coupon code PIONEER</b> :)',
                '&nbsp;',
                'Hope you enjoy the video tutorials as much as I had the passion recording them, and please feel free to reach me with issues, improvements, or video suggestions! I answer ALL emails.', ],
            ],

        ]);
    }
}
