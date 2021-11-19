<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserInfoToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('city')->comment('City');
            $table->string('address')->nullable()->comment('Address');
            $table->string('job')->nullable()->comment('Job');
            $table->string('self_introduce', 512)->nullable()->comment('Self Introduce');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['city', 'address', 'job', 'self_introduce']);
        });
    }
}
