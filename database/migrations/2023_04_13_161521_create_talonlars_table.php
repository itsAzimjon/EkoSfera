<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalonlarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talonlars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buyurtmachi_id');
            $table->unsignedBigInteger('yolvaraqa_id');
            $table->string('buyurtma_id')->default('0');
            $table->string('yuk');
            $table->string('olchov');
            $table->string('type');
            $table->string('status')->default(1);
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
        Schema::dropIfExists('talonlars');
    }
}
