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
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('users', 'government_id_type')) {
                $table->string('government_id_type')->nullable()->after('contact_number');
            }
            if (!Schema::hasColumn('users', 'government_id_number')) {
                $table->string('government_id_number')->nullable()->after('government_id_type');
            }
            if (!Schema::hasColumn('users', 'government_id_file_path')) {
                $table->string('government_id_file_path')->nullable()->after('government_id_number');
            }
            
            // Email verification fields
            if (!Schema::hasColumn('users', 'verification_code')) {
                $table->string('verification_code')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'verification_code_expires_at')) {
                $table->timestamp('verification_code_expires_at')->nullable()->after('verification_code');
            }
            if (!Schema::hasColumn('users', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('verification_code_expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'government_id_type',
                'government_id_number',
                'government_id_file_path',
                'verification_code',
                'verification_code_expires_at',
                'is_verified'
            ]);
        });
    }
};
