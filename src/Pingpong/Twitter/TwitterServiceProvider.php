<?php namespace Pingpong\Twitter;

use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('twitter', function ($app)
        {
            $options = $app['config']->get('twitter');

            $twitter = new Twitter($options, $app['redirect'], $app['request'], $app['session.store']);

            return $twitter;
        });
    }

}