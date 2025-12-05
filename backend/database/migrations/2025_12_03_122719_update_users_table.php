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

            //Remove old columns
            $table->removeColumn('name');
            
            //Add new columns

            //Name info
            $table->string('first_name')->after('id')->maxlength(50);
            $table->string('last_name')->after('first_name')->maxlength(50);
            $table->string('patronymic')->after('last_name')->maxlength(50)->nullable();


            //Contact info
            $table->string('phone_number')->after('email_verified_at')->maxlength(17)->nullable();

            //Additional info
            $table->enum('role', ['admin', 'manager', 'line_worker'])->after('password')->default('line_worker');
            $table->string('position')->after('role')->maxlength(100);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            //Add old columns back
            $table->string('name')->after('id')->maxlength(100);

            //Remove new columns
            $table->removeColumn('first_name');
            $table->removeColumn('last_name');
            $table->removeColumn('patronymic');
            $table->removeColumn('phone_number');
            $table->removeColumn('role');
            $table->removeColumn('position');
        });
    }
};
