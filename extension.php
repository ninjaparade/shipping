<?php

use Cartalyst\Extensions\ExtensionInterface;
use Illuminate\Foundation\Application;

return [

	/*
	|--------------------------------------------------------------------------
	| Name
	|--------------------------------------------------------------------------
	|
	| This is your extension name and it is only required for
	| presentational purposes.
	|
	*/

	'name' => 'Shipping',

	/*
	|--------------------------------------------------------------------------
	| Slug
	|--------------------------------------------------------------------------
	|
	| This is your extension unique identifier and should not be changed as
	| it will be recognized as a new extension.
	|
	| Ideally, this should match the folder structure within the extensions
	| folder, but this is completely optional.
	|
	*/

	'slug' => 'ninjaparade/shipping',

	/*
	|--------------------------------------------------------------------------
	| Author
	|--------------------------------------------------------------------------
	|
	| Because everybody deserves credit for their work, right?
	|
	*/

	'author' => 'Ninja',

	/*
	|--------------------------------------------------------------------------
	| Description
	|--------------------------------------------------------------------------
	|
	| One or two sentences describing the extension for users to view when
	| they are installing the extension.
	|
	*/

	'description' => 'Shipping extension to handle shipping costs and options',

	/*
	|--------------------------------------------------------------------------
	| Version
	|--------------------------------------------------------------------------
	|
	| Version should be a string that can be used with version_compare().
	| This is how the extensions versions are compared.
	|
	*/

	'version' => '0.1.0',

	/*
	|--------------------------------------------------------------------------
	| Requirements
	|--------------------------------------------------------------------------
	|
	| List here all the extensions that this extension requires to work.
	| This is used in conjunction with composer, so you should put the
	| same extension dependencies on your main composer.json require
	| key, so that they get resolved using composer, however you
	| can use without composer, at which point you'll have to
	| ensure that the required extensions are available.
	|
	*/

	'require' => [
		'platform/admin',
	],

	/*
	|--------------------------------------------------------------------------
	| Autoload Logic
	|--------------------------------------------------------------------------
	|
	| You can define here your extension autoloading logic, it may either
	| be 'composer', 'platform' or a 'Closure'.
	|
	| If composer is defined, your composer.json file specifies the autoloading
	| logic.
	|
	| If platform is defined, your extension receives convetion autoloading
	| based on the Platform standards.
	|
	| If a Closure is defined, it should take two parameters as defined
	| bellow:
	|
	|	object \Composer\Autoload\ClassLoader      $loader
	|	object \Illuminate\Foundation\Application  $app
	|
	| Supported: "composer", "platform", "Closure"
	|
	*/

	'autoload' => 'composer',

	/*
	|--------------------------------------------------------------------------
	| Register Callback
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is registered. This can do
	| all the needed custom logic upon registering.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'register' => function(ExtensionInterface $extension, Application $app)
	{
		$ZipcodeRepository = 'Ninjaparade\Shipping\Repositories\ZipcodeRepositoryInterface';

		if ( ! $app->bound($ZipcodeRepository))
		{
			$app->bind($ZipcodeRepository, function($app)
			{
				$model = get_class($app['Ninjaparade\Shipping\Models\Zipcode']);

				return new Ninjaparade\Shipping\Repositories\DbZipcodeRepository($model, $app['events']);
			});
		}
	},

	/*
	|--------------------------------------------------------------------------
	| Boot Callback
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is booted. This can do
	| all the needed custom logic upon booting.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'boot' => function(ExtensionInterface $extension, Application $app)
	{
		//register cart
		$app->register('Ninjaparade\Shipping\Laravel\Providers\ShippingServiceProvider');
		Illuminate\Foundation\AliasLoader::getInstance()->alias('Shipping', 'Ninjaparade\Shipping\Laravel\Facades\ShippingServiceFacade');

		if (class_exists('Ninjaparade\Shipping\Models\Zipcode'))
		{
			// Get the model
			$model = $app['Ninjaparade\Shipping\Models\Zipcode'];

			// Register a new attribute namespace
			$app['Platform\Attributes\Models\Attribute']->registerNamespace($model);
		}

	},

	/*
	|--------------------------------------------------------------------------
	| Routes
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| any custom routing logic here.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'routes' => function(ExtensionInterface $extension, Application $app)
	{
		Route::group(['namespace' => 'Ninjaparade\Shipping\Controllers'], function()
		{
			Route::group(['prefix' => admin_uri().'/shipping/zipcodes', 'namespace' => 'Admin'], function()
			{
				Route::get('/', 'ZipcodesController@index');
				Route::post('/', 'ZipcodesController@executeAction');
				Route::get('grid', 'ZipcodesController@grid');
				Route::get('create', 'ZipcodesController@create');
				Route::post('create', 'ZipcodesController@store');
				Route::get('{id}/edit', 'ZipcodesController@edit');
				Route::post('{id}/edit', 'ZipcodesController@update');
				Route::get('{id}/delete', 'ZipcodesController@delete');
			});

			Route::group(['prefix' => 'shipping/zipcodes', 'namespace' => 'Frontend'], function()
			{
				Route::get('/', 'ZipcodesController@index');
			});
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Database Seeds
	|--------------------------------------------------------------------------
	|
	| Platform provides a very simple way to seed your database with test
	| data using seed classes. All seed classes should be stored on the
	| `database/seeds` directory within your extension folder.
	|
	| The order you register your seed classes on the array below
	| matters, as they will be ran in the exact same order.
	|
	| The seeds array should follow the following structure:
	|
	|	Vendor\Namespace\Database\Seeds\FooSeeder
	|	Vendor\Namespace\Database\Seeds\BarSeeder
	|
	*/

	'seeds' => [

		'Ninjaparade\Shipping\Database\Seeds\ZipCodeTableSeeder',

	],

	/*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
	| List of permissions this extension has. These are shown in the user
	| management area to build a graphical interface where permissions
	| may be selected.
	|
	| The admin controllers state that permissions should follow the following
	| structure:
	|
	|    Vendor\Namespace\Controller@method
	|
	| For example:
	|
	|    Platform\Users\Controllers\Admin\UsersController@index
	|
	| These are automatically generated for controller routes however you are
	| free to add your own permissions and check against them at any time.
	|
	| When writing permissions, if you put a 'key' => 'value' pair, the 'value'
	| will be the label for the permission which is displayed when editing
	| permissions.
	|
	*/

	'permissions' => function()
	{
		return [
			'Ninjaparade\Shipping\Controllers\Admin\ZipcodesController@index,grid'   => Lang::get('ninjaparade/shipping::zipcodes/permissions.index'),
			'Ninjaparade\Shipping\Controllers\Admin\ZipcodesController@create,store' => Lang::get('ninjaparade/shipping::zipcodes/permissions.create'),
			'Ninjaparade\Shipping\Controllers\Admin\ZipcodesController@edit,update'  => Lang::get('ninjaparade/shipping::zipcodes/permissions.edit'),
			'Ninjaparade\Shipping\Controllers\Admin\ZipcodesController@delete'       => Lang::get('ninjaparade/shipping::zipcodes/permissions.delete'),
		];
	},

	/*
	|--------------------------------------------------------------------------
	| Widgets
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| all your custom widgets here. Of course, Platform will guess the
	| widget class for you, this is just for custom widgets or if you
	| do not wish to make a new class for a very small widget.
	|
	*/

	'widgets' => function()
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Settings
	|--------------------------------------------------------------------------
	|
	| Register any settings for your extension. You can also configure
	| the namespace and group that a setting belongs to.
	|
	*/

	'settings' => function()
	{
		return [
			'shipping' => ['name' => 'Shipping'],
			'shipping::general' => ['name' => 'General'],
			
			

			'shipping::general.local_pickup_text' => [
				'name'    => 'Local Pickup Text',
				'config'  => 'ninjaparade/shipping::local_pickup_text',
				'info'    => 'Local Pickup Text',
				'type'    => 'input'
			],

			'shipping::general.local_shipping_text' => [
				'name'    => 'Local Shipping Text',
				'config'  => 'ninjaparade/shipping::local_shipping_text',
				'info'    => 'Local Shipping Text',
				'type'    => 'input'
			],

			'shipping::general.regular_shipping_text' => [
				'name'    => 'Regular Shipping Text',
				'config'  => 'ninjaparade/shipping::regular_shipping_text',
				'info'    => 'Regular Shipping Text',
				'type'    => 'input'
			],

			'shipping::general.express_shipping_text' => [
				'name'    => 'Express Shipping Text',
				'config'  => 'ninjaparade/shipping::express_shipping_text',
				'info'    => 'Express Shipping Text',
				'type'    => 'input'
			],

			'shipping::general.local_pickup' => [
				'name'    => 'Local Pickup Charge',
				'config'  => 'ninjaparade/shipping::local_pickup',
				'info'    => 'Local Pickup Charge',
				'type'    => 'input'
			],

			'shipping::general.local_shipping' => [
				'name'    => 'Local Shipping Charge',
				'config'  => 'ninjaparade/shipping::local_shipping',
				'info'    => 'Local Shipping Charge',
				'type'    => 'input'
			],

			'shipping::general.regular_shipping_first_item' => [
				'name'    => 'Regular Shipping First Item',
				'config'  => 'ninjaparade/shipping::regular_shipping_first_item',
				'info'    => 'Regular Shipping First Item',
				'type'    => 'input'
			],

			'shipping::general.regular_shipping_per_item' => [
				'name'    => 'Regular Shipping After First Item',
				'config'  => 'ninjaparade/shipping::regular_shipping_per_item',
				'info'    => 'Regular Shipping After First Item',
				'type'    => 'input'
			],

			'shipping::general.express_shipping_first_item' => [
				'name'    => 'Express Shipping First Item',
				'config'  => 'ninjaparade/shipping::express_shipping_first_item',
				'info'    => 'Express Shipping First Item',
				'type'    => 'input'
			],

			'shipping::general.express_shipping_per_item' => [
				'name'    => 'Express Shipping After First Item',
				'config'  => 'ninjaparade/shipping::express_shipping_per_item',
				'info'    => 'Express Shipping After First Item',
				'type'    => 'input'
			]
		];
	},

	/*
	|--------------------------------------------------------------------------
	| Menus
	|--------------------------------------------------------------------------
	|
	| You may specify the default various menu hierarchy for your extension.
	| You can provide a recursive array of menu children and their children.
	| These will be created upon installation, synchronized upon upgrading
	| and removed upon uninstallation.
	|
	| Menu children are automatically put at the end of the menu for extensions
	| installed through the Operations extension.
	|
	| The default order (for extensions installed initially) can be
	| found by editing app/config/platform.php.
	|
	*/

	'menus' => [

		'admin' => [
			[
				'slug' => 'admin-ninjaparade-shipping',
				'name' => 'Shipping',
				'class' => 'fa fa-circle-o',
				'uri' => 'shipping',
				'children' => [
					[
						'slug' => 'admin-ninjaparade-shipping-zipcode',
						'name' => 'Zipcodes',
						'class' => 'fa fa-circle-o',
						'uri' => 'shipping/zipcodes',
					],
				],
			],
		],
		'main' => [
			[
				'slug' => 'main-ninjaparade-shipping',
				'name' => 'Shipping',
				'class' => 'fa fa-circle-o',
				'uri' => 'shipping',
			],
		],
	],

];
