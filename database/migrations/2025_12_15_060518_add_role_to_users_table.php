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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('password');
            $table->string('department')->nullable()->after('role');
            $table->string('program')->nullable()->after('department');
            $table->string('year_level')->nullable()->after('program');
            $table->string('section')->nullable()->after('year_level');
            $table->string('student_id')->nullable()->after('section');
            $table->string('status')->default('Active')->after('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'department', 'program', 'year_level', 'section', 'student_id', 'status']);
        });
    }
};
