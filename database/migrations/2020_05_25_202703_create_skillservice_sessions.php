<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillserviceSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skillservice_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid');
            $table->text('url')->nullable();
            $table->integer('status_id');
            $table->morphs('model');
            $table->text('redirect_uri')->nullable();
            $table->json('tasks')->nullable();
            $table->json('feedback')->nullable();
            $table->json('template')->nullable();
            $table->json('score')->nullable();
            $table->integer('score_percent')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
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
        Schema::dropIfExists('skillservice_sessions');
    }
}
