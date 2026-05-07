<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->enum('remedy_type', ['Crystal', 'Lal Kitab', 'Switch Word', 'Vedic Switch Word'])
                  ->after('subcategory_id');
        });
    }

    public function down(): void
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->dropColumn('remedy_type');
        });
    }
};
