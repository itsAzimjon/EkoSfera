<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYolvaraqaasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yolvaraqaas', function (Blueprint $table) {
            $table->id();
            $table->date('sana')->format('d/m/Y');
            $table->time('work_time')->format('h:m');
            $table->time('free_time')->format('h:m');
            $table->unsignedBigInteger('tuman_id');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('haydovchi_id');
            $table->unsignedBigInteger('texnika_id');
            $table->integer('start_km');
            $table->integer('finish_km');
            $table->integer('yoqilgi');
            $table->integer('miqdori');
            $table->string("tibbiy");
            $table->string("texnik");
            $table->integer("type")->default(0);
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
        Schema::dropIfExists('yolvaraqaas');
    }
}
