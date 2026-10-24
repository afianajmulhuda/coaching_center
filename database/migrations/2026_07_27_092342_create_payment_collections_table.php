<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_collections', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 30)->unique();
            $table->foreignId('student_fee_id')->constrained('student_fees')->onDelete('cascade');
            $table->decimal('paid_amount', 10, 2);
            $table->enum('payment_method', ['cash', 'bkash', 'nagad', 'bank'])->default('cash');
            $table->string('transaction_ref', 100)->nullable();
            $table->foreignId('collected_by_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_collections');
    }
};