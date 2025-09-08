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
            $table->dropColumn([
                'government_id_type',
                'government_id_number',
                'government_id_file_path'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('government_id_type')->nullable()->after('contact_number');
            $table->string('government_id_number')->nullable()->after('government_id_type');
            $table->string('government_id_file_path')->nullable()->after('government_id_number');
        });
    }
};