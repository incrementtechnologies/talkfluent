<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAccountPaypalCreationsTableAddPaypalPlanId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_paypal_creations', function (Blueprint $table) {
            $table->unsignedInteger('paypal_plan_id')->after('account_id');
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
