<?php

use /** @noinspection PhpUndefinedClassInspection */
    Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostedMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @noinspection PhpUndefinedClassInspection */
        Schema::create('posted_medias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('media_content_path');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->boolean('media_type')
                ->comment('0 => Image, 1 => Video, 2 => Audio');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer('in_post')
                ->unsigned()
                ->index();
            $table->timestamps();

            /*
             * Foreign key constraints...
             */
            /** @noinspection PhpUndefinedMethodInspection */
            $table->foreign('in_post')
                ->on('posts')
                ->references('id')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /** @noinspection PhpUndefinedClassInspection */
        Schema::dropIfExists('posted_medias');
    }
}
