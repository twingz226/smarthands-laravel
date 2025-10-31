<?php

namespace App\Mail;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $cancellationReason;

    /**
     * Create a new message instance.
     *
     * @param Job $job
     * @param string|null $cancellationReason
     * @return void
     */
    public function __construct(Job $job, ?string $cancellationReason = null)
    {
        $this->job = $job;
        $this->cancellationReason = $cancellationReason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.job-cancelled')
                    ->subject('Your Cleaning Service Has Been Cancelled')
                    ->with([
                        'job' => $this->job,
                        'cancellationReason' => $this->cancellationReason ?? 'No reason provided.',
                    ]);
    }
}
