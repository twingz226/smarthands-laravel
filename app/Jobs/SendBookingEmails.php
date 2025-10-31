<?php

namespace App\Jobs;

use App\Mail\BookingConfirmation;
use App\Mail\NewBookingAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBookingEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $booking;
    protected $customer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($booking, $customer)
    {
        $this->booking = $booking;
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if (config('mail.enabled')) {
                Mail::to(config('mail.admin_email'))->send(new NewBookingAlert($this->booking));
                Mail::to($this->customer->email)->send(new BookingConfirmation($this->booking));
            }
        } catch (\Exception $mailException) {
            Log::error('Mail sending failed in SendBookingEmails job: ' . $mailException->getMessage());
        }
    }
}