<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAccountPaypalCreationsTableRenamePaypalPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_paypal_creations', function (Blueprint $table) {
            $table->string('paypal_plan_id')->change();
            $table->renameColumn('paypal_plan_id', 'paypal_plan');
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
