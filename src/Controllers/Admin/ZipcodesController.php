<?php namespace Ninjaparade\Shipping\Controllers\Admin;

use DataGrid;
use Input;
use Lang;
use Platform\Admin\Controllers\Admin\AdminController;
use Redirect;
use Response;
use View;
use Ninjaparade\Shipping\Repositories\ZipcodeRepositoryInterface;

class ZipcodesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Shipping repository.
	 *
	 * @var \Ninjaparade\Shipping\Repositories\ZipcodeRepositoryInterface
	 */
	protected $zipcode;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Ninjaparade\Shipping\Repositories\ZipcodeRepositoryInterface  $zipcode
	 * @return void
	 */
	public function __construct(ZipcodeRepositoryInterface $zipcode)
	{
		parent::__construct();

		$this->zipcode = $zipcode;
	}

	/**
	 * Display a listing of zipcode.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return View::make('ninjaparade/shipping::zipcodes.index');
	}

	/**
	 * Datasource for the zipcode Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->zipcode->grid();

		$columns = [
			'id',
			'zip',
			'city',
			'state',
			'country',
			'local',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		return DataGrid::make($data, $columns, $settings);
	}

	/**
	 * Show the form for creating new zipcode.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new zipcode.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating zipcode.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating zipcode.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified zipcode.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		if ($this->zipcode->delete($id))
		{
			$message = Lang::get('ninjaparade/shipping::zipcodes/message.success.delete');

			return Redirect::toAdmin('shipping/zipcodes')->withSuccess($message);
		}

		$message = Lang::get('ninjaparade/shipping::zipcodes/message.error.delete');

		return Redirect::toAdmin('shipping/zipcodes')->withErrors($message);
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = Input::get('action');

		if (in_array($action, $this->actions))
		{
			foreach (Input::get('entries', []) as $entry)
			{
				$this->zipcode->{$action}($entry);
			}

			return Response::json('Success');
		}

		return Response::json('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a zipcode identifier?
		if (isset($id))
		{
			if ( ! $zipcode = $this->zipcode->find($id))
			{
				$message = Lang::get('ninjaparade/shipping::zipcodes/message.not_found', compact('id'));

				return Redirect::toAdmin('shipping/zipcodes')->withErrors($message);
			}
		}
		else
		{
			$zipcode = $this->zipcode->createModel();
		}

		// Show the page
		return View::make('ninjaparade/shipping::zipcodes.form', compact('mode', 'zipcode'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Get the input data
		$data = Input::all();

		// Do we have a zipcode identifier?
		if ($id)
		{
			// Check if the data is valid
			$messages = $this->zipcode->validForUpdate($id, $data);

			// Do we have any errors?
			if ($messages->isEmpty())
			{
				// Update the zipcode
				$zipcode = $this->zipcode->update($id, $data);
			}
		}
		else
		{
			// Check if the data is valid
			$messages = $this->zipcode->validForCreation($data);

			// Do we have any errors?
			if ($messages->isEmpty())
			{
				// Create the zipcode
				$zipcode = $this->zipcode->create($data);
			}
		}

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			// Prepare the success message
			$message = Lang::get("ninjaparade/shipping::zipcodes/message.success.{$mode}");

			return Redirect::toAdmin("shipping/zipcodes/{$zipcode->id}/edit")->withSuccess($message);
		}

		return Redirect::back()->withInput()->withErrors($messages);
	}

}
