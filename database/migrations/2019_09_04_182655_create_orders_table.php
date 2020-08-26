<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('payment', ['cash', 'visa']);
            $table->float('delivery_fees')->nullable();
            $table->float('price')->nullable();
            $table->float('total_price')->nullable();
            $table->float('comision')->nullable();
            $table->float('net')->nullable();
            $table->string('delivery_address');
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'delivered', 'declined']);
            $table->integer('resturant_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('orders');
    }
}
