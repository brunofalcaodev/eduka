<?php

namespace Eduka\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DefaultMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $subject;
    public $markdownPath;

    public function __construct(
        string $subject,
        array $data = [],
        string $path = 'eduka::markdown.default'
    ) {
        $this->subject = $subject;
        $this->markdownPath = $path;
        $this->data = $data;
    }

    public function build()
    {
        // Add default data to .. data :|
        $this->data['logo'] = course()->url.
                              '/'.
                              config('course.assets.mail.logo');

        return $this->from(
            env('EDUKA_ADMIN_EMAIL'),
            env('EDUKA_ADMIN_NAME').' from '.course()->name
        )->subject($this->subject)
         ->markdown($this->markdownPath, [
            'data' => $this->data,
        ]);
    }
}
