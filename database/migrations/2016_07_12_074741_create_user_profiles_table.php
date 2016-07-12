<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            //$table->increments('id');
            $table->integer('user_id')->unsigned()->primary();
            $table->string('nickname');
            $table->string('name');
            $table->enum('sex', ['secret','female', 'male'])->default('secret');
            $table->integer('org_created_num')->unsigned();
            $table->integer('org_joined_num')->unsigned();
            $table->integer('integration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_profiles');
    }
}
