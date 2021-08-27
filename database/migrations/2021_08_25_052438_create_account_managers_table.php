<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_managers', function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->nullable();
            $table->string("account_pin")->nullable();
            $table->string("account_code")->nullable();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("middle_name")->nullable();
            $table->string("photo")->nullable();
            $table->string("mobile_no")->nullable();
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
        Schema::dropIfExists('account_managers');
    }
}
