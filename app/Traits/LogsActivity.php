<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait.
     */
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            $model->logActivity('created', $model->getAttributes());
        });

        static::updated(function (Model $model) {
            $model->logActivity('updated', $model->getChanges());
        });

        static::deleted(function (Model $model) {
            $model->logActivity('deleted', $model->getAttributes());
        });
    }

    /**
     * Log activity for the model.
     */
    public function logActivity(string $action, array $data = [])
    {
        if (!class_exists('App\Models\ActivityLog')) {
            return;
        }

        \App\Models\ActivityLog::create([
            'user_id' => Auth::id(),
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'action' => $action,
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get the activity logs for this model.
     */
    public function activityLogs()
    {
        return $this->morphMany(\App\Models\ActivityLog::class, 'model');
    }
}
