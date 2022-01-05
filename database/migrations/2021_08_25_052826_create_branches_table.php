<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->nullable();
            $table->string("client_uuid")->nullable();
            $table->string("branch_code")->nullable();
            $table->string("branch_name")->nullable();
            $table->string("branch_address")->nullable();
            $table->string("area_uuid")->nullable();
            $table->string("region_uuid")->nullable();
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
        Schema::dropIfExists('branches');
    }
}
