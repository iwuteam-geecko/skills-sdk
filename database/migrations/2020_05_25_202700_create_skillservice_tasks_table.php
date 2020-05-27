<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillserviceTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skillservice_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('skillservice_id');
            $table->integer('type');
            $table->text('name');
            $table->json('test')->nullable();
            $table->json('tags')->default(json_encode([]));
            $table->text('task_instructions')->nullable();
            $table->text('internal_notes')->nullable();
            $table->integer('time_limit')->nullable();
            $table->integer('difficulty')->nullable();
            $table->boolean('is_demo')->nullable();
            $table->json('structure')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
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
        Schema::dropIfExists('skillservice_tasks');
    }
}
