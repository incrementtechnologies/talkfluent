<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAccountStripeCardsAddCardDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_stripe_cards', function (Blueprint $table) {
            $table->string('card_number')->after('account_id');
            $table->string('exp_year')->after('card_number');
            $table->string('exp_month')->after('exp_year');
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
