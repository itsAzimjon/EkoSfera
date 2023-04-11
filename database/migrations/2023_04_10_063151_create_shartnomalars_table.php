<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShartnomalarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shartnomalars', function (Blueprint $table) {
            $table->id();
            $table->string('stir');
            $table->string('name');
            $table->string('type');
            $table->date('start')->format('d/m/Y');
            $table->date('finish')->format('d/m/Y');
            $table->string('raqami');
            $table->string('manzil');
            $table->string('summa');
            $table->string('rahbar');
            $table->string('bugalter');
            $table->string('tel');
            $table->string('mfo');
            $table->string('hison_raqam');
            $table->string('qqs');
            $table->string('varaqa');
            $table->string('gazna');
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
        Schema::dropIfExists('shartnomalars');
    }
}
