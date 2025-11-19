<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityJournal extends Model
{
    use HasFactory;

    protected $table = 'activity_journals';

    protected $fillable = [
        'user_id',
        'Objet',
        'Date',
        'Lieu',
        'Participants',
        'Description',
        'Recommandations',
        'Conclusion',
    ];

    protected $casts = [
        'Date' => 'date',
    ];

    /**
     * Get the user that owns the activity journal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the formatted date for display.
     */
    public function getFormattedDateAttribute()
    {
        return $this->Date ? $this->Date->format('d/m/Y') : null;
    }
}
