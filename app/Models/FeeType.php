<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'default_amount',
    ];

    public function studentFees(): HasMany
    {
        return $this->hasMany(StudentFee::class, 'fee_type_id');
    }
}