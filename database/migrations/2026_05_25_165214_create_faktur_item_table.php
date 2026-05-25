<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFakturItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faktur_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_faktur');
            $table->foreign('id_faktur')->references('id')->on('faktur')->onDelete('cascade');
            $table->string('id_barang', 20);
            $table->foreign('id_barang')->references('id')->on('barang')->onDelete('cascade');
            $table->integer('qty');
            $table->integer('harga')->nullable()->default(0);
            $table->integer('jumlah')->nullable()->default(0);
            $table->timestamps();
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
        Schema::dropIfExists('faktur_item');
    }
}
