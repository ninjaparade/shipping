<?php namespace Ninjaparade\Shipping\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class ShippingServiceProvider extends ServiceProvider {

	public function register()
	{
		$this->app['np_shipping'] = $this->app->share(function($app)
        {
            $ZipcodeRepository = 'Ninjaparade\Shipping\Repositories\ZipcodeRepositoryInterface';

            $model = get_class($app['Ninjaparade\Shipping\Models\Zipcode']);

            return new \Ninjaparade\Shipping\Repositories\DbZipcodeRepository($model, $app['events']);
            
        });

	}

}