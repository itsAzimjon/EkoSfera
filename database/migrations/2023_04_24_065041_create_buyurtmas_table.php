<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyurtmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyurtmas', function (Blueprint $table) {
            $table->id();
            $table->date('sana')->format('d/m/Y');
            $table->unsignedBigInteger('buyurtmachi_id');
            $table->string('masul');
            $table->string('phone');
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
        Schema::dropIfExists('buyurtmas');
    }
}
