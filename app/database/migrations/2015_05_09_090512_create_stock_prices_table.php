<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockPricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_prices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('full_name');
			$table->decimal('price', 10, 2);
			$table->decimal('high', 10, 2);
			$table->decimal('low', 10, 2);
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
		Schema::drop('stock_prices');
	}

}
