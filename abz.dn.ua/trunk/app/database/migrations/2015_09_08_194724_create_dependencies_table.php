<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDependenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        /**
         * Table where worker binds to his boss
         */
        Schema::create('dependencies', function($table)
        {
            $table->integer('worker_id')->unsigned();
            $table->index('worker_id');
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('restrict');

            $table->integer('boss_id')->unsigned();
            $table->index('boss_id');
            $table->foreign('boss_id')->references('id')->on('workers')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dependencies');
    }

}
