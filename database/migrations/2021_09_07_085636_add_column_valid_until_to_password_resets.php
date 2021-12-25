<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnValidUntilToPasswordResets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->datetime("valid_until")->nullable()->after("token");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->dropColumn("valid_until");
        });
    }
}











