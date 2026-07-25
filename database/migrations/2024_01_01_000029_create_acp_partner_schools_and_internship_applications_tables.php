<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('acp_partner_schools')) {
            Schema::create('acp_partner_schools', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('npsn')->nullable();
                $table->string('city')->nullable();
                $table->string('province')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('internship_applications')) {
            Schema::create('internship_applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('acp_partner_school_id')->nullable()->constrained('acp_partner_schools')->onDelete('set null');
                $table->string('school_name');
                $table->string('contact_person');
                $table->string('phone');
                $table->string('email')->nullable();
                $table->integer('student_count')->default(1);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('proposal_file')->nullable();
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_applications');
        Schema::dropIfExists('acp_partner_schools');
    }
};
