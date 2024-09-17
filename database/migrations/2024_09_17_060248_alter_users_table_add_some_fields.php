<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableAddSomeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('approved')->default(0)->after('email_verified_at');
            $table->string('legal_form')->nullable();
            $table->string('sector_activity')->nullable();
            $table->string('company_size')->nullable();
            $table->string('legal_address')->nullable();
            $table->string('operational_address')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['approved',
                                'legal_form', 'sector_activity', 'company_size',
                                'legal_address',  'operational_address', 'contact_person_name',
                                'position']);
        });
    }
}
