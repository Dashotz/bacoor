<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('id');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('surname')->nullable()->after('middle_name');
            $table->string('suffix')->nullable()->after('surname');
        });
        
        // Copy existing name data to first_name and surname
        DB::statement("UPDATE users SET first_name = name, surname = name WHERE first_name IS NULL");
        
        // Now make required fields NOT NULL
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
            $table->string('surname')->nullable(false)->change();
        });
        
        // Drop the old name column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->dropColumn(['first_name', 'middle_name', 'surname', 'suffix']);
        });
    }
};
