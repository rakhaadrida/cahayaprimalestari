<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->date('tgl_so');
            $table->date('tgl_kirim');
            $table->integer('total');
            $table->integer('discount');
            $table->string('status');
            $table->string('id_customer');
            $table->softDeletes();
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
        Schema::dropIfExists('so');
    }
}
