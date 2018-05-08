<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSponsorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sponsors', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('user_id');
            $table->string('country', 255)
                ->default('');
            $table->string('box_count', 255)
                ->default('');
            $table->string('state', 255)
                ->default('');
            $table->string('city', 255)
                ->default('');
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
