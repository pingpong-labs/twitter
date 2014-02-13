<?php namespace Pingpong\Twitter;

use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Array providers.
	 *
	 * @var array
	 */
	protected $providers = array(
		'twitter'	=>	'Pingpong\Twitter\Twitter'
	);
	
	/**
	 * Array facades.
	 *
	 * @var array
	 */
	protected $facades = array(
		'Twitter'	=>	'Pingpong\Twitter\Facades\Twitter'
	);

	/**
	 * Booting the service provider.
	 *
	 * @return void
	 */

	public function boot()
	{
		$this->package('pingpong/twitter', 'twitter');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerProviders();
		$this->registerFacades();
	}

	/**
	 * Register all service providers.
	 *
	 * @return void
	 */
	protected function registerProviders()
	{
		$providers = $this->providers;
		foreach ($providers as $key => $value) {
			$this->app[$key] = $this->app->share(function($app) use ($value)
			{
				return new $value($app);
			});
		}
	}

	/**
	 * Register all facades.
	 *
	 * @return void
	 */
	protected function registerFacades()
	{
		$facades = $this->facades;
		$this->app->booting(function() use ($facades)
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			foreach ($facades as $key => $value) {
				$loader->alias($key, $value);
			}
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		$providers = array();
		foreach ($this->providers as $key => $value) {
			$providers[] = $key;
		}
		return $providers;
	}

}