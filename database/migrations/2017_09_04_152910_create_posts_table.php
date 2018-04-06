<?php

use /** @noinspection PhpUndefinedClassInspection */
    Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @noinspection PhpUndefinedClassInspection */
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            /** @noinspection PhpUndefinedMethodInspection */
            $table->longText('content')
                ->nullable();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->boolean('has_media_content')
                ->comment('0 => No, 1 => Yes')
                ->default(0);
            /** @noinspection PhpUndefinedMethodInspection */
            $table->integer('posted_by')
                ->unsigned()
                ->index();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->boolean('is_hidden')
                ->comment('0 => No, 1 => Yes')
                ->default(0);
            /** @noinspection PhpUndefinedMethodInspection */
            $table->boolean('is_auto_generated')
                ->comment('0 => No, 1 => Yes')
                ->default(0);
            /** @noinspection PhpUndefinedMethodInspection */
            $table->boolean('is_edited')
                ->comment('0 => No, 1 => Yes')
                ->default(0);
            /** @noinspection PhpUndefinedMethodInspection */
            $table->boolean('is_approved')
                ->comment('0 => No, 1 => Yes')
                ->default(0);
            $table->timestamps();

            /*
             * Foreign key constraints...
             */
            /** @noinspection PhpUndefinedMethodInspection */
            $table->foreign('posted_by')
                ->on('users')
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
        Schema::dropIfExists('posts');
    }
}
