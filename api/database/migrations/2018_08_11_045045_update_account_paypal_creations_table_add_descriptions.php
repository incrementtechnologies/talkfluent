<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAccountPaypalCreationsTableAddDescriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_paypal_creations', function (Blueprint $table) {
            $table->string('amount')->after('method');
            $table->string('description_amount')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_paypal_creations', function (Blueprint $table) {
            //
        });
    }
}
