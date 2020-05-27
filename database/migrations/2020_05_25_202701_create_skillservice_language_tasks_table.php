<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillserviceLanguageTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skillservice_language_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('skillservice_id');
            $table->unsignedBigInteger('task_id');
            $table->text('name');
            $table->unsignedInteger('language_id');
            $table->string('language_name');
            $table->string('language_code');
            $table->text('instructions')->nullable();
            $table->text('code')->nullable();
            $table->integer('type')->nullable();
            $table->integer('time_limit')->nullable();
            $table->boolean('has_tests')->nullable();
            $table->boolean('is_tests_hidden')->nullable();
            $table->json('tests')->nullable();
            $table->integer('difficulty')->nullable();
            $table->boolean('is_demo')->nullable();
            $table->json('structure')->nullable();
            $table->timestamp('created_at')->nullable();
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
        Schema::dropIfExists('skillservice_language_tasks');
    }
}
