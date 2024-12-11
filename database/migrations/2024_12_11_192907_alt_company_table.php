<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AltCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company', function (Blueprint $table) {
            $table->string('legal_form')->nullable();
            $table->string('sector_activity')->nullable();
            $table->string('company_size')->nullable();
            $table->string('legal_address')->nullable();
            $table->string('operational_address')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('country_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company', function (Blueprint $table) {
            $table->dropColumn(['legal_form', 'sector_activity', 'company_size',
                                'legal_address',  'operational_address', 'contact_person_name', 'country_code']);
        });
    }
}
