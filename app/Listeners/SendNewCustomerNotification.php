<?php

namespace App\Listeners;

use App\Events\NewCustomerRegistered;
use App\Models\User;
use App\Notifications\NewCustomerNotification;

class SendNewCustomerNotification
{
    /**
     * Handle the event.
     *
     * @param  NewCustomerRegistered  $event
     * @return void
     */
    public function handle(NewCustomerRegistered $event)
    {
        $customer = $event->customer;
        $admins = User::where('role', 'admin')->get();
        
        $message = "New customer registered: {$customer->name} ({$customer->email})";
        $link = route('admin.customers.show', $customer->id);

        foreach ($admins as $admin) {
            // Use Laravel's Notifiable::notify via the alias defined in App\Models\User
            // to avoid the custom HasNotifications::notify signature conflict.
            $admin->laravelNotify(new NewCustomerNotification($customer, $link));
        }
    }
}
