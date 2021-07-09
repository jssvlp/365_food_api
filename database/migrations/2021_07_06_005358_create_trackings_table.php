<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('trackings', function (Blueprint $table) {
            $table->id();
            $table->string('orderNumber');
            $table->enum('status',['Pendiente','En proceso', 'Listo', 'En camino','Entregado'])->default('Pendiente');
            $table->string('clientName');
            $table->string('clientCode');
            $table->string('phone');
            $table->string('address');
            $table->json('orderDetail');
            $table->unsignedBigInteger('delivery_man_id')->nullable();
            $table->timestamp('deliveredAt')->nullable();
            $table->timestamps();

            $table->foreign('delivery_man_id')->references('id')->on('delivery_men');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trackings');
    }
}
