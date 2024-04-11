<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtilitiesSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utilities_sale', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('link')->nullable();
            $table->boolean('is_folder')->nullable();
            $table->integer('parent_folder_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('utilities_sale_user_permission', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('utilities_sale_id');
            $table->bigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utilities_sale');
        Schema::dropIfExists('utilities_sale_user_permission');
    }
}
