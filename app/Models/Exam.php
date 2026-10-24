<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'class_id',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
    public function classModel(): BelongsTo
    {
        // Replace ClassModel::class with your actual Class model class (e.g., AcademicClass or Classes)
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ExamSchedule::class, 'exam_id');
    }

    public function resultSheets(): HasMany
    {
        return $this->hasMany(ResultSheet::class, 'exam_id');
    }
}