<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'attachment_url',
        'target_audience',
        'batch_id',
        'is_pinned',
        'created_by_user_id',
        'published_date',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'published_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Relationship: Notice created by a specific User (Admin / Teacher)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Relationship: Optional target Batch
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    /**
     * Scope: Filter active and published notices
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'published')
                     ->where('published_date', '<=', now())
                     ->where(function ($q) {
                         $q->whereNull('expiry_date')
                           ->orWhere('expiry_date', '>=', now());
                     });
    }
}