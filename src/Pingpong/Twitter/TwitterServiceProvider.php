<?php namespace Pingpong\Twitter;

use Codebird\Codebird;
use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Boot the package.
	 * 
	 * @return void 
	 */
	public function boot()
	{
		$this->package('pingpong/twitter');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['pingpong.twitter'] = $this->app->share(function($app)
		{
			$api = new Api;

            $config = $app['config']->get('twitter::config');

			return new Twitter(
                $api,
                $app['session.store'],
                $app['config'],
                $app['request'],
                $app['redirect'],
                $config['consumer_key'],
                $config['consumer_secret'],
                $config['oauth_token'],
                $config['oauth_token_secret'],
                $config['bearer_token'],
                $config['callback_url'],
                $config['fallback_url']
            );
		});	
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('pingpong.twitter');
	}

}
