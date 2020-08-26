<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeys extends Migration
{
    public function up()
    {
        Schema::table('resturants', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('resturants', function (Blueprint $table) {
            $table->foreign('neighborhood_id')->references('id')->on('neighborhoods')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('resturant_id')->references('id')->on('resturants')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('resturant_id')->references('id')->on('resturants')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('foods')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('commissions', function (Blueprint $table) {
            $table->foreign('resturant_id')->references('id')->on('resturants')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('neighborhoods', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->foreign('resturant_id')->references('id')->on('resturants')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->foreign('food_id')->references('id')->on('foods')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });

        Schema::table('food_resturant', function (Blueprint $table) {
            $table->foreign('food_id')->references('id')->on('foods')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('food_resturant', function (Blueprint $table) {
            $table->foreign('resturant_id')->references('id')->on('resturants')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });

        Schema::table('category_resturant', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('category_resturant', function (Blueprint $table) {
            $table->foreign('resturant_id')->references('id')->on('resturants')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });

        Schema::table('client_order', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('client_order', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')
                        ->onDelete('cascade')
                        ->onUpdate('cascade');
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('neighborhood_id')->references('id')->on('neighborhoods')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('resturant_id')->references('id')->on('resturants')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('resturants', function (Blueprint $table) {
            $table->dropForeign('resturants_city_id_foreign');
        });
        Schema::table('resturants', function (Blueprint $table) {
            $table->dropForeign('resturants_neighborhood_id_foreign');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_client_id_foreign');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('comments_resturant_id_foreign');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_resturant_id_foreign');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_client_id_foreign');
        });
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropForeign('commissions_resturant_id_foreign');
        });
        Schema::table('neighborhoods', function (Blueprint $table) {
            $table->dropForeign('neighborhoods_city_id_foreign');
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign('offers_resturant_id_foreign');
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign('offers_food_id_foreign');
        });
        Schema::table('food_resturant', function (Blueprint $table) {
            $table->dropForeign('food_resturant_food_id_foreign');
        });
        Schema::table('food_resturant', function (Blueprint $table) {
            $table->dropForeign('food_resturant_resturant_id_foreign');
        });
        Schema::table('category_resturant', function (Blueprint $table) {
            $table->dropForeign('category_resturant_category_id_foreign');
        });
        Schema::table('category_resturant', function (Blueprint $table) {
            $table->dropForeign('category_resturant_resturant_id_foreign');
        });
        Schema::table('client_order', function (Blueprint $table) {
            $table->dropForeign('client_order_client_id_foreign');
        });
        Schema::table('client_order', function (Blueprint $table) {
            $table->dropForeign('client_order_order_id_foreign');
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign('clients_city_id_foreign');
            $table->dropForeign('clients_neighborhood_id_foreign');
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_resturant_id_foreign');
        });
    }
}
