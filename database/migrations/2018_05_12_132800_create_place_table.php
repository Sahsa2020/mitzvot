<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('places', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('country_id')->unsigned()->nullable();
            $table->integer('state_id')->unsigned()->nullable();
            $table->string('city', 255)->default('');
            $table->string('district', 255)->default('');
            $table->integer('population')->default(0)->nullable();
            $table->integer('units')->default(0)->nullable();
            $table->integer('cost_assumption')->default(0)->nullable();
            $table->integer('profit_assumption')->default(0)->nullable();
            $table->integer('status')->default(0);
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
