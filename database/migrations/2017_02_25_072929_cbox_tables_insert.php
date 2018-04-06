<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CboxTablesInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cbox_boxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_id')->unique();
            $table->integer('user_id')->unsigned();
            $table->integer('d_count')->default(0);
            $table->string('country_code', 150)->default('');
            $table->integer('del_flg')->default(0);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('cbox_deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_id');
            $table->integer('user_id');
            $table->decimal('amount', 5, 2)->default(0);
            $table->string('currencyt', 150)->default('');
            $table->integer('coin_size')->default(0);
            $table->integer('del_flg')->default(0);
            $table->timestamps();
        });

        Schema::create('cbox_donates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('org_id')->unsigned();
            $table->string('name', 150)->default('');
            $table->string('picture', 150)->default('');
            $table->string('city', 150)->default('');
            $table->string('country', 150)->default('');
            $table->string('description', 150)->default('');
            $table->integer('commitment')->default(0);
            $table->integer('donate_count')->default(0);
            $table->integer('exist_count')->default(0);
            $table->integer('del_flg')->default(0);
            $table->foreign('org_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('cbox_member_boxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->unsigned();
            $table->integer('device_id');
            $table->integer('del_flg')->default(0);
            $table->foreign('member_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('cbox_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('buyer_id');
            $table->integer('user_id');
            $table->decimal('amount', 5, 2)->default(0);
            $table->integer('count');
            $table->integer('type');
            $table->integer('status');
            $table->string('invoice_key', 150)->default('');
            $table->integer('del_flg')->default(0);
            $table->timestamps();
        });

        Schema::create('cbox_coins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('size');
            $table->string('country_code', 150)->default('');
            $table->decimal('amount', 5, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('cbox_currencyts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('currencyt', 150)->default('');
            $table->decimal('rate', 5, 2)->default(0);
            $table->string('country_name', 150)->default('');
            $table->integer('del_flg')->default(0);
            $table->timestamps();
        });

        Schema::create('cbox_sell_boxes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 150)->default('');
            $table->string('detail', 150)->default('');
            $table->string('type', 150)->default('');
            $table->decimal('price', 5, 2)->default(0);
            $table->integer('sold_count')->default(0);
            $table->integer('amount')->default(0);
            $table->string('main_image_url', 150)->default('');
            $table->integer('del_flg')->default(0);
            $table->timestamps();
        });

        Schema::create('cbox_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('box_id')->unsigned();
            $table->string('image_url', 150)->default('');
            $table->integer('order')->default(0);
            $table->integer('del_flg')->default(0);
            $table->foreign('box_id')
                ->references('id')
                ->on('cbox_sell_boxes')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('cbox_buyers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('donateIds', 150)->default('');
            $table->string('donateQuantities', 150)->default('');
            $table->integer('user_id')->default(0);
            $table->string('name', 150)->default('');
            $table->string('email', 150)->default('');
            $table->string('address', 150)->default('');
            $table->string('comment', 150)->default('');
            $table->integer('del_flg')->default(0);
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
        //
    }
}
