<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargabarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hargabarang', function (Blueprint $table) {
            $table->string('id_barang');
            $table->string('id_harga');
            $table->integer('harga');
            $table->softDeletes();
            $table->timestamps();
            $table->primary('id_barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hargabarang');
    }
}
