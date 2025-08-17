<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\CustomerActivity;
use App\Models\Job;
use App\Models\Rating;

class CustomerActivityObserver
{
    public function created(Booking|Job|Rating $model)
    {
        try {
            $description = match(get_class($model)) {
                Booking::class => "New booking created for " . $model->service->name,
                Job::class => "New job scheduled for " . $model->service->name,
                Rating::class => "New feedback submitted for job #" . $model->job_id,
                default => "New activity recorded"
            };

            $type = match(get_class($model)) {
                Booking::class => 'booking',
                Job::class => 'job',
                Rating::class => 'feedback',
                default => 'other'
            };

            CustomerActivity::create([
                'customer_id' => $model->customer_id,
                'type' => $type,
                'description' => $description,
                'metadata' => [
                    'model_type' => get_class($model),
                    'model_id' => $model->id,
                    'status' => $model->status ?? null,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating customer activity: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
        }
    }

    public function updated(Booking|Job|Rating $model)
    {
        if ($model->isDirty('status')) {
            try {
                $description = match(get_class($model)) {
                    Booking::class => "Booking status updated to " . $model->status,
                    Job::class => "Job status updated to " . $model->status,
                    Rating::class => "Feedback status updated to " . $model->status,
                    default => "Status updated to " . $model->status
                };

                CustomerActivity::create([
                    'customer_id' => $model->customer_id,
                    'type' => match(get_class($model)) {
                        Booking::class => 'booking',
                        Job::class => 'job',
                        Rating::class => 'feedback',
                        default => 'other'
                    },
                    'description' => $description,
                    'metadata' => [
                        'model_type' => get_class($model),
                        'model_id' => $model->id,
                        'old_status' => $model->getOriginal('status'),
                        'new_status' => $model->status,
                    ]
                ]);
            } catch (\Exception $e) {
                \Log::error('Error creating customer activity: ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
            }
        }
    }
} 