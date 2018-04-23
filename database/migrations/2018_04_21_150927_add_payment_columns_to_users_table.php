<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {

            $table->string('bank_account', 255)
                ->default('');
            $table->string('routing_number', 255)
                ->default('');
            $table->string('account_number', 255)
                ->default('');
            $table->string('name_of_bank_account', 255)
                ->default('');
            $table->string('bank_name', 255)
                ->default('');
            $table->string('account_type', 255)
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
