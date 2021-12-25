<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->nullable();
            $table->string("survey_uuid")->nullable();
            $table->string("question_group_uuid")->nullable();
            $table->string("question_subgroup_uuid")->nullable();
            $table->text("question")->nullable();
            $table->json("answer")->nullable();
            $table->string("question_type")->nullable();
            $table->json("question_options")->nullable();
            $table->boolean("required_photo")->nullable()->default(false);
            $table->json("photos")->nullable();
            $table->string("remarks")->nullable();
            $table->boolean("required_remarks")->nullable()->default(false);
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
        Schema::dropIfExists('survey_questions');
    }
}
