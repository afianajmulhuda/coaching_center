<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarkEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_schedule_id',
        'student_id',
        'obtained_marks',
        'is_absent',
        'remarks',
    ];

    protected $casts = [
        'is_absent' => 'boolean',
    ];

    public function examSchedule(): BelongsTo
    {
        return $this->belongsTo(ExamSchedule::class, 'exam_schedule_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}