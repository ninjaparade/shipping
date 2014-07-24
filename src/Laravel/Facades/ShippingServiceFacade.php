<?php namespace Ninjaparade\Shipping\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class ShippingServiceFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'np_shipping'; }

}