<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->tinyInteger('standard_climatic')->default(0);            
            $table->integer('s_Tfin');
            $table->integer('s_Trin');
            $table->integer('s_Hfin');
            $table->integer('s_Hrin');

            $table->integer('p_r_airflow');
            $table->integer('p_r_pressure');
            $table->integer('p_sfp');
            $table->integer('m_rfl');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['standard_climatic', 's_Tfin','s_Trin','s_Hfin','s_Hrin', 'p_r_airflow','p_r_pressure','p_sfp','m_rfl']);
        });
    }
}
