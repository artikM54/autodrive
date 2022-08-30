<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->unique();
            $table->string('mark');
            $table->string('model');
            $table->string('generation')->nullable();
            $table->smallInteger('year')->unsigned()->nullable();
            $table->mediumInteger('run')->unsigned()->nullable();
            $table->string('color')->nullable();
            $table->string('body_type')->nullable();
            $table->string('engine_type')->nullable();
            $table->string('transmission')->nullable();
            $table->string('gear_type')->nullable();
            $table->bigInteger('generation_id')->unsigned()->nullable();
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
        Schema::dropIfExists('offers');
    }
};
