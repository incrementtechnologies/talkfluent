<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBillingInformationsTableInitial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_informations', function (Blueprint $table) {
            $table->string('company')->after('id')->nullable();
            $table->longText('address')->after('company');
            $table->string('city')->after('address');
            $table->string('postal_code')->after('city');
            $table->string('country')->after('postal_code');
            $table->string('state')->after('country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_informations', function (Blueprint $table) {
            //
        });
    }
}
