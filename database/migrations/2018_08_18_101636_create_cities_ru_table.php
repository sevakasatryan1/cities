<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesRuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities_ru', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('geonameid');
            $table->string('name',200);
            $table->string('asciiname',200);
            $table->text('alternatenames')->nullable();
            $table->float('latitude');
            $table->float('longitude');
            $table->string('feature_class',1)->nullable();
            $table->string('feature_code',10)->nullable();
            $table->string('country_code',3);
            $table->string('cc2',200)->nullable();
            $table->string('admin1_code',20)->nullable();
            $table->string('admin2_code',80)->nullable();
            $table->string('admin3_code',20)->nullable();
            $table->string('admin4_code',20)->nullable();
            $table->bigInteger('population')->nullable();
            $table->integer('elevation')->nullable();
            $table->integer('dem')->nullable();
            $table->string('timezone',40)->nullable();
            $table->timestamp('modification_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities_ru');
    }
}
