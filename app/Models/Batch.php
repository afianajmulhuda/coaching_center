<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'batch_name',
        'max_capacity',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function routines(): HasMany
    {
        return $this->hasMany(ClassRoutine::class, 'batch_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'batch_id');
    }
}