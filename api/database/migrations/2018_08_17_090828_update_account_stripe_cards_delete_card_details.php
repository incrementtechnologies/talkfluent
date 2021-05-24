<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAccountStripeCardsDeleteCardDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_stripe_cards', function (Blueprint $table) {
            $table->dropColumn(['brand', 'last4', 'exp_year', 'exp_month', 'source', 'customer']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_stripe_cards', function (Blueprint $table) {
            //
        });
    }
}
