<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassModel extends Model
{
    use HasFactory;

    // Directing model to use 'classes' table name
    protected $table = 'classes';

    protected $fillable = ['class_name'];

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'class_id');
    }
}