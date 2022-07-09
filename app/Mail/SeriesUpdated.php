<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SeriesUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $changes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($changes)
    {
        $this->changes = $changes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('Summary of changes'))->markdown('series-updated');
    }
}
