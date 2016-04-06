<?php namespace Softon\Indipay;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class IndipayServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		Config::package('softon/indipay', 'indipay');
        $gateway = Config::get('indipay::gateway');
        $this->app->bind('indipay', '\Softon\Indipay\Indipay');

        $this->app->bind('\Softon\Indipay\Gateways\PaymentGatewayInterface','\Softon\Indipay\Gateways\\'.$gateway.'Gateway');
	}


    public function boot(){

		$this->package('softon/indipay','indipay');

    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [

        ];
	}

}
