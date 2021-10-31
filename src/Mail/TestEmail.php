<?php

namespace Eduka\Mail;

use Brunocfalcao\Quickmail\Quickmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestEmail extends Quickmail implements ShouldQueue
{
    use Queueable;

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
        $this
            ->from(
                'bruno@masteringnova.com',
                'Bruno from Mastering Nova Course'
            )
            ->subject(
                'Quickmail template'
            )
            ->preview('
                This is my preview in the newsletter email');

        parent::build();
    }
}
