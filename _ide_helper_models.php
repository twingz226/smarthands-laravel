<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $phone
 * @property string $address
 * @property string|null $profile_photo
 * @property int $points
 * @property string $role
 * @property-read Collection|Notification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Notification[] $readNotifications
 * @property-read int|null $read_notifications_count
 * @property-read Collection|Notification[] $unreadNotifications
 * @property-read int|null $unread_notifications_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereAddress($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User wherePoints($value)
 * @method static Builder|User whereProfilePhoto($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRole($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method bool hasUnreadNotifications()
 * @method int markNotificationsAsRead()
 * @method int unreadNotificationsCount()
 */
class User {}

/**
 * App\Models\Notification
 *
 * @property int $id
 * @property string $type
 * @property int $user_id
 * @property int|null $booking_id
 * @property int|null $customer_id
 * @property string $message
 * @property string|null $link
 * @property Carbon|null $read_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read \App\Models\Booking|null $booking
 * @property-read \App\Models\Customer|null $customer
 * @property-read User $user
 * @method static Builder|Notification newModelQuery()
 * @method static Builder|Notification newQuery()
 * @method static Builder|Notification query()
 * @method static Builder|Notification whereBookingId($value)
 * @method static Builder|Notification whereCreatedAt($value)
 * @method static Builder|Notification whereCustomerId($value)
 * @method static Builder|Notification whereId($value)
 * @method static Builder|Notification whereLink($value)
 * @method static Builder|Notification whereMessage($value)
 * @method static Builder|Notification whereReadAt($value)
 * @method static Builder|Notification whereType($value)
 * @method static Builder|Notification whereUpdatedAt($value)
 * @method static Builder|Notification whereUserId($value)
 * @method static Builder|Notification read()
 * @method static Builder|Notification unread()
 * @method static Builder|Notification recent(int $days = 7)
 * @method bool isRead()
 * @method bool markAsRead()
 * @method bool markAsUnread()
 * @method bool unread()
 */
class Notification {}
