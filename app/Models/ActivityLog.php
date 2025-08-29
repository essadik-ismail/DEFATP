<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject model of the activity.
     */
    public function subject()
    {
        if ($this->model_type && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }

    /**
     * Scope a query to only include activities for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include activities of a specific type.
     */
    public function scopeOfType($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to only include activities for a specific model.
     */
    public function scopeForModel($query, $modelType, $modelId = null)
    {
        $query->where('model_type', $modelType);
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        return $query;
    }

    /**
     * Get the formatted date for display.
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y à H:i:s');
    }

    /**
     * Get the action icon for display.
     */
    public function getActionIconAttribute()
    {
        return match($this->action) {
            'login' => 'fas fa-sign-in-alt',
            'logout' => 'fas fa-sign-out-alt',
            'create' => 'fas fa-plus',
            'update' => 'fas fa-edit',
            'delete' => 'fas fa-trash',
            'view' => 'fas fa-eye',
            'export' => 'fas fa-download',
            'import' => 'fas fa-upload',
            'status_change' => 'fas fa-toggle-on',
            default => 'fas fa-info-circle',
        };
    }

    /**
     * Get the action color for display.
     */
    public function getActionColorAttribute()
    {
        return match($this->action) {
            'login' => 'success',
            'logout' => 'secondary',
            'create' => 'primary',
            'update' => 'warning',
            'delete' => 'danger',
            'view' => 'info',
            'export' => 'success',
            'import' => 'info',
            'status_change' => 'warning',
            default => 'secondary',
        };
    }
}
