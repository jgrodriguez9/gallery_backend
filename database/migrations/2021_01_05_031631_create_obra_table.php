<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obra', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');

            //foreing key
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->foreign('categoria_id')->references('id')->on('categoria');

            $table->unsignedBigInteger('tecnica_id')->nullable();
            $table->foreign('tecnica_id')->references('id')->on('tecnica');

            $table->unsignedBigInteger('tematica_id')->nullable();
            $table->foreign('tematica_id')->references('id')->on('tematica');

            $table->unsignedBigInteger('soporte_id')->nullable();
            $table->foreign('soporte_id')->references('id')->on('soporte');

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
        Schema::dropIfExists('obra');
    }
}
