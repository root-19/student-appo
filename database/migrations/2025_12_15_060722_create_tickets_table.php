<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique(); // TICKET-1, TICKET-2, etc.
            $table->string('title');
            $table->enum('category', ['Registrar', 'Administrative', 'Academic']);
            $table->string('subcategory')->nullable();
            $table->enum('priority', ['Low', 'Medium', 'High']);
            $table->enum('status', ['Pending', 'In Progress', 'Resolved', 'Rejected'])->default('Pending');
            $table->text('description');
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            $table->string('student_name');
            $table->date('submitted_date');
            $table->date('last_updated');
            $table->date('appointment_date')->nullable();
            $table->time('appointment_time')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('attachment_url')->nullable();
            $table->string('attachment_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
