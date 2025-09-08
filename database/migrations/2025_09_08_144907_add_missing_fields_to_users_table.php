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
            $table->enum('civil_status', ['single', 'married', 'widowed', 'divorced'])->nullable()->after('gender');
            $table->string('birthplace')->nullable()->after('birth_date');
            $table->enum('citizenship', ['Filipino', 'Dual Citizen', 'Foreigner'])->nullable()->after('birthplace');
            $table->string('application_photo_path')->nullable()->after('government_id_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'civil_status',
                'birthplace',
                'citizenship',
                'application_photo_path'
            ]);
        });
    }
};
