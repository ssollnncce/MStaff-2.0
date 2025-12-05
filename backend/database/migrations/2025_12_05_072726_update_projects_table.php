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
        Schema::table('projects', function (Blueprint $table) {
            
            //Recreate old columns
            $table->dropColumn('end_date');
            $table->date('due_date')->after('start_date');

            //Create new columns
            $table->enum('priority', ['low', 'medium', 'high'])->after('due_date');
            $table->enum('status', ['planning', 'in_progress', 'completed', 'decline', 'on_hold'])->after('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            
            //Recreate old columns
            $table->date('end_date')->after('start_date');

            //Drop new columns
            $table->dropColumn('due_date');
            $table->dropColumn('priority');
            $table->dropColumn('status');
        });
    }
};
