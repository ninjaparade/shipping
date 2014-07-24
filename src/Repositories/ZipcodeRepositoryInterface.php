<?php namespace Ninjaparade\Shipping\Repositories;

interface ZipcodeRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Ninjaparade\Shipping\Models\Zipcode
	 */
	public function grid();

	/**
	 * Returns all the shipping entries.
	 *
	 * @return \Ninjaparade\Shipping\Models\Zipcode
	 */
	public function findAll();

	/**
	 * Returns a shipping entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Ninjaparade\Shipping\Models\Zipcode
	 */
	public function find($id);

	/**
	 * Determines if the given shipping is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given shipping is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates a shipping entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Ninjaparade\Shipping\Models\Zipcode
	 */
	public function create(array $data);

	/**
	 * Updates the shipping entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Ninjaparade\Shipping\Models\Zipcode
	 */
	public function update($id, array $data);

	/**
	 * Deletes the shipping entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
