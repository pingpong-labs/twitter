<?php

use Illuminate\Http\Request;
use Mockery as m;
use Pingpong\Twitter\Twitter;

class TwitterTest extends PHPUnit_Framework_TestCase
{
    protected $api;

    protected $session;

    protected $config;

    protected $request;

    protected $redirect;

    protected $twitter;

    function tearDown()
    {
        m::close();
    }

    function setUp()
    {
        $this->api = m::mock('Pingpong\Twitter\Api');
        $this->session = m::mock('Illuminate\Session\Store');
        $this->config = m::mock('Illuminate\Config\Repository');
        $this->request = m::mock('Illuminate\Http\Request');
        $this->redirect = m::mock('Illuminate\Routing\Redirector');

        $consumerKey = 'my-consumer-key';
        $consumerSecret = 'my-consumer-secret';
        $oauthToken = 'my-oauth-token';
        $oauthTokenSecret = 'my-oauth-token-secret';

        $this->api->shouldReceive('setConsumerKey');
        $this->api->shouldReceive('setToken');

        $this->twitter = new Twitter($this->api, $this->session, $this->config, $this->request, $this->redirect, $consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret);
    }

    function testInit()
    {
        $config = require __DIR__ . '/../src/config/config.php';
        $this->assertInstanceOf('Pingpong\Twitter\Twitter', $this->twitter);
    }

    public function getDummyUrlGenerator()
    {
        return new Illuminate\Routing\UrlGenerator(
            new Illuminate\Routing\RouteCollection,
            new Request
        );
    }

    function testGetCallbackUrl()
    {
        $this->config->shouldReceive('get')->once()->with('twitter.callback_url')->andReturn('twitter/callback');
        $this->redirect
            ->shouldReceive('getUrlGenerator')
            ->once()
            ->andReturn($this->getDummyUrlGenerator());
    
        $callbackUrl = $this->twitter->getCallbackUrl();
        $this->assertEquals('http://:/twitter/callback', $callbackUrl);
    }

    function testGetFallbackUrl()
    {
        $this->config->shouldReceive('get')->once()->with('twitter.fallback_url')->andReturn('/');
         $this->redirect
            ->shouldReceive('getUrlGenerator')
            ->once()
            ->andReturn($this->getDummyUrlGenerator());
        $url = $this->twitter->getFallbackUrl();
        $this->assertEquals('http://:', $url);
    }

    function testGetAuthorizeUrl()
    {
        $this->api->shouldReceive('oauth_authorize')->once()->andReturn('foo://');
        $url = $this->twitter->getAuthorizeUrl();
        $this->assertTrue(is_string($url));
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedException \Pingpong\Twitter\Exceptions\TwitterApiException
     */
    function testAuthorization()
    {
        $apiResponse = (object) array(
            'oauth_token' => 'bla',
            'oauth_token_secret' => 'bla',
            'httpstatus' => 200
        );
        $response = $this->twitter->authorize();
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $response);
    }
}