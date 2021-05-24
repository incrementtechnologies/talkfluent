<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePaypalAgreementsTableDeleteNameAddPaypalAccountId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paypal_agreements', function (Blueprint $table) {
            $table->dropColumn(['name']);
            $table->unsignedInteger('paypal_account_id')->after('account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paypal_agreements', function (Blueprint $table) {
            //
        });
    }
}
