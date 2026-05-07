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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // client who generated
            $table->enum('module', ['health', 'relationship', 'career', 'money']);
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->date('customer_dob');
            $table->string('customer_contact');
            $table->json('subcategory_ids'); // selected subcategory IDs
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
