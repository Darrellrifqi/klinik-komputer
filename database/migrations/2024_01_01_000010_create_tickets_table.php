<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // SRV-YYYYMMDD-XXXX
            $table->integer('queue_number');
            $table->unsignedBigInteger('customer_id')->nullable(); // null jika walk-in tanpa akun
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->enum('unit_type', ['laptop', 'desktop', 'printer', 'other'])->default('laptop');
            $table->string('brand');
            $table->string('model');
            $table->text('damage_description');
            $table->enum('status', ['waiting', 'checking', 'checked', 'rma', 'done', 'cancelled'])->default('waiting');
            $table->unsignedBigInteger('assigned_to')->nullable(); // teknisi
            $table->unsignedBigInteger('created_by')->nullable(); // cs atau customer
            $table->date('start_check_date')->nullable();
            $table->string('pic_name')->nullable(); // penanggung jawab
            $table->json('components_issue')->nullable(); // komponen bermasalah
            $table->text('cause')->nullable();
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
