<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAccountStripeCardsUpdateCardDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_stripe_cards', function (Blueprint $table) {
            $table->renameColumn('card_number', 'last4');
            $table->string('brand')->after('account_id')->nullable();
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
