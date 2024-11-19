<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indicator_id');
            $table->string('countrycode');
            $table->integer('year');
            $table->decimal('country_score',11,9);
            $table->string('country_col');
            $table->string('country_cat');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('indicator_id')->references('id')->on('indicators')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_data');
    }
};
