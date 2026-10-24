<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'student_fee_id',
        'paid_amount',
        'payment_method',
        'transaction_ref',
        'collected_by_user_id',
    ];

    public function studentFee(): BelongsTo
    {
        return $this->belongsTo(StudentFee::class, 'student_fee_id');
    }

    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by_user_id');
    }
}