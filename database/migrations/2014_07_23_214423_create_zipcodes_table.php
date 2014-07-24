<?php

 use Illuminate\Database\Schema\Blueprint;
 use Illuminate\Database\Migrations\Migration;

class CreateZipcodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zipcodes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('zip');
			$table->string('city');
			$table->string('state');
			$table->string('country');
			$table->tinyInteger('local')->default('0');
			$table->timestamps();

			$table->softDeletes();
            $table->index('zip');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('zipcodes');
	}

}
