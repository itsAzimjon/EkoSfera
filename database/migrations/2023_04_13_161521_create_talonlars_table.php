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
            $table->date('sana')->format('d/m/Y');
            $table->unsignedBigInteger('buyurtmachi_id');
            $table->string('texnika');
            $table->string('haydovchi');
            $table->string('buyurtma_id')->default('0');
            $table->string('yuk');
            $table->string('type');
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
