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
        Schema::dropIfExists('client_staff');
    }
}
