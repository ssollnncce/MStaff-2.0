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
        Schema::table('employees', function (Blueprint $table) {
            
            //Delete old columns
            $table->dropColumn('birth_date');

            //Create new columns
            $table->foreignId('status_id')->nullable()->constrained('employee_statuses')->after('fired_date');
            $table->enum('work_format', ['office', 'remote', 'hybrid'])->after('status_id');
            $table->string('time_zone')->after('work_format');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            //Recreate old columns
            $table->date('birth_date')->after('phone_number');

            //Drop new columns
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
            $table->dropColumn('work_format');
            $table->dropColumn('time_zone');
        });
    }
};
