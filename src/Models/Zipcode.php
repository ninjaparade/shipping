<?php namespace Ninjaparade\Shipping\Models;

use Platform\Attributes\Models\Entity;

class Zipcode extends Entity {

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'zipcodes';

	/**
	 * {@inheritDoc}
	 */
	protected $guarded = [
		'id',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $with = [
		'values.attribute',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $eavNamespace = 'ninjaparade/shipping.zipcode';

}
