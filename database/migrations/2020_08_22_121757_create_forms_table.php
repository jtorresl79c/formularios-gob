<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->string('title');
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->text('success_msg')->nullable();
            $table->text('thanks_msg')->nullable();
            $table->integer('is_active')->default(1);
            $table->bigInteger('allow_comments')->nullable();
            $table->bigInteger('allow_share_section')->nullable();
            $table->string('created_by')->nullable();
            $table->text('json');
            $table->string('theme')->default('theme1');
            $table->string('theme_color')->default('theme-2');
            $table->string('theme_background_image')->default('form-themes/theme3/form-background.png');
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
        Schema::dropIfExists('forms');
    }
}
