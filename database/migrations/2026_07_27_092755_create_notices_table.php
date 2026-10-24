<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description');
            $table->string('attachment_url')->nullable(); // PDF or Image file path
            
            // Audience Targeting
            $table->enum('target_audience', ['all', 'teachers', 'students', 'guardians', 'batch'])->default('all');
            $table->foreignId('batch_id')->nullable()->constrained('batches')->onDelete('cascade'); // If targeting specific batch
            
            // Notice Controls
            $table->boolean('is_pinned')->default(false); // Pin notice to top
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('cascade'); // Who posted the notice
            $table->date('published_date');
            $table->date('expiry_date')->nullable(); // Auto-hide after date
            $table->enum('status', ['published', 'draft', 'archived'])->default('published');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};