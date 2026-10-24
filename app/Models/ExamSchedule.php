<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'subject_id',
        'batch_id',
        'exam_date',
        'start_time',
        'total_marks',
        'pass_marks',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function markEntries(): HasMany
    {
        return $this->hasMany(MarkEntry::class, 'exam_schedule_id');
    }
    protected $casts = [
        'exam_date' => 'date', // Casts exam_date to a Carbon object
    ];
}