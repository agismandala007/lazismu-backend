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
        Schema::create('frontoffices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('penyetor');
            $table->string('penerima');
            $table->string('nobuktipenerima');
            $table->date('tanggal');
            $table->string('ref')->nullable();
            $table->integer('jumlah');
            $table->string('tempatbayar');
            $table->bigInteger('coadebit_id')->unsigned();
            $table->bigInteger('coakredit_id')->unsigned();
            $table->bigInteger('cabang_id')->unsigned();
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
        Schema::dropIfExists('frontoffices');
    }
};
