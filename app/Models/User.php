<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The database connection for the model.
     *
     * @var string
     */
    // protected $connection = 'auth_mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'ppr',
        'image',
        'email_verified_at',
        'password',
        'is_deleted',
        'email_notifications',
        'push_notifications',
        'notification_types',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_deleted' => 'boolean',
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'notification_types' => 'array',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Global scope to exclude deleted users
        static::addGlobalScope('not_deleted', function (Builder $builder) {
            $builder->where('is_deleted', false);
        });
    }

    /**
     * Get the activity logs for the user.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the recent activity logs for the user.
     */
    public function recentActivityLogs(int $limit = 10)
    {
        return $this->activityLogs()->latest()->limit($limit);
    }

    /**
     * Get the activity logs for a specific action.
     */
    public function activityLogsByAction(string $action, int $limit = 10)
    {
        return $this->activityLogs()->where('action', $action)->latest()->limit($limit);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(AppNotification::class);
    }

    /**
     * Get the unread notifications for the user.
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    /**
     * Get the read notifications for the user.
     */
    public function readNotifications()
    {
        return $this->notifications()->whereNotNull('read_at');
    }
}
