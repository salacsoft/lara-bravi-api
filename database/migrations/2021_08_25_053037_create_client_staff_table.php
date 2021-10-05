<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_staff', function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->nullable();
            $table->string("client_uuid")->nullable();
            $table->string("user_type")->nullable();
            $table->string("staff_code")->nullable();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("middle_name")->nullable();
            $table->string("photo")->nullable();
            $table->string("mobile_no")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_staff');
    }
}
