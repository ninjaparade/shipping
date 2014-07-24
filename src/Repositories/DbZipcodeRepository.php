<?php namespace Ninjaparade\Shipping\Repositories;

use Cartalyst\Interpret\Interpreter;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Ninjaparade\Shipping\Models\Zipcode;
use Symfony\Component\Finder\Finder;
use Validator;
use Config;

class DbZipcodeRepository implements ZipcodeRepositoryInterface {

	/**
	 * The Eloquent shipping model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * The event dispatcher instance.
	 *
	 * @var \Illuminate\Events\Dispatcher
	 */
	protected $dispatcher;

	/**
	 * Holds the form validation rules.
	 *
	 * @var array
	 */
	protected $rules = [

	];


	protected $config;
	/**
	 * Constructor.
	 *
	 * @param  string  $model
	 * @param  \Illuminate\Events\Dispatcher  $dispatcher
	 * @return void
	 */
	public function __construct($model, Dispatcher $dispatcher)
	{
		$this->model = $model;

		$this->dispatcher = $dispatcher;

		$this->config = Config::get('ninjaparade/shipping::config');

		


		// dd($this->config['regular_shipping_first_item']);
		// die;
		// dd($this->config);
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this
			->createModel()
			->newQuery()
			->get();
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this
			->createModel()
			->where('id', (int) $id)
			->first();
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $data)
	{
		return $this->validateZipcode($data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $data)
	{
		return $this->validateZipcode($data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $data)
	{
		with($zipcode = $this->createModel())->fill($data)->save();

		$this->dispatcher->fire('ninjaparade.shipping.zipcode.created', $zipcode);

		return $zipcode;
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $data)
	{
		$zipcode = $this->find($id);

		$zipcode->fill($data)->save();

		$this->dispatcher->fire('ninjaparade.shipping.zipcode.updated', $zipcode);

		return $zipcode;
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		if ($zipcode = $this->find($id))
		{
			$this->dispatcher->fire('ninjaparade.shipping.zipcode.deleted', $zipcode);

			$zipcode->delete();

			return true;
		}

		return false;
	}


	public function shippingOptions($zip, $packages)
	{
		 $local = $this->isLocal($zip);


		if($local)
        {
            $options = array(
                array('value' => $this->config['local_pickup'], 	'type' => $this->config['local_pickup_text'] ),
                array('value' => $this->config['local_shipping'], 	'type' => $this->config['local_shipping_text'] )
            );    

        }else{

            $options = array(
            	array('value'  =>  $this->getRate($packages, false), 'type' => $this->config['regular_shipping_text'] ),
            	array('value'  =>  $this->getRate($packages, true), 'type' => $this->config['express_shipping_text'] ),
            );   

        }

        return $options;
	}	

	public function getRate($packages, $express = true)
	{  
        if($express)
        {
        	
        	$first = $this->config['express_shipping_first_item'];
        	$next  = $this->config['express_shipping_per_item'];
        	
        }else{

        	$first = $this->config['regular_shipping_first_item'];
        	$next  = $this->config['regular_shipping_per_item'];
            
        }

        return number_format(($packages - 1) * $next + $first, 2);
	}

	public function isLocal($zip)
	{
        $post_zip = str_replace('-', '', $zip);
		
		$local = $this->createModel()->where('zip', 'LIKE', '%'.strtoupper($post_zip).'%')->first();
		
		if(count($local))
		{
			
			return true;
		}else{
			
			return false;
		}
		
	}

	/**
	 * Create a new instance of the model.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function createModel(array $data = [])
	{
		$class = '\\'.ltrim($this->model, '\\');

		return new $class($data);
	}

	/**
	 * Validates a shipping entry.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	protected function validateZipcode($data)
	{
		$validator = Validator::make($data, $this->rules);

		$validator->passes();

		return $validator->errors();
	}

}
