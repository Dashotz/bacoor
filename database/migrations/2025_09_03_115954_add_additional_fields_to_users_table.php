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
            $table->date('birth_date')->nullable()->after('suffix');
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
            $table->enum('account_type', ['individual', 'business'])->default('individual')->after('gender');
            $table->string('contact_number', 20)->nullable()->after('account_type');
            $table->string('government_id_type')->nullable()->after('contact_number');
            $table->string('government_id_number')->nullable()->after('government_id_type');
            $table->string('government_id_file_path')->nullable()->after('government_id_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'gender',
                'account_type',
                'contact_number',
                'government_id_type',
                'government_id_number',
                'government_id_file_path'
            ]);
        });
    }
};
