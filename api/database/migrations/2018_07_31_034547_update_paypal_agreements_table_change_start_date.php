<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePaypalAgreementsTableChangeStartDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paypal_agreements', function (Blueprint $table) {
            $table->string('start_date')->change();
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
