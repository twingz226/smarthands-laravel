<?php

namespace App\Mail;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CleanerAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $cleaners;

    /**
     * Create a new message instance.
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
        $this->cleaners = $job->employees;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Cleaners Assigned to Your Service')
                    ->view('emails.jobs.cleaner_assigned')
                    ->with([
                        'cleaners' => $this->job->employees,
                        'job' => $this->job,
                    ]);
    }
} 