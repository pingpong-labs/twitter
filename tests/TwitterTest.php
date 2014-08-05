<?php

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
        $this->assertInstanceOf('Pingpong\Twitter\Twitter', $this->twitter);
    }

    function testGetCallbackUrl()
    {
        $this->config->shouldReceive('get')->once()->with('twitter::callback_url')->andReturn('twitter/callback');
        $callbackUrl = $this->twitter->getCallbackUrl();
        $this->assertEquals('twitter/callback', $callbackUrl);
    }

    function testGetFallbackUrl()
    {
        $this->config->shouldReceive('get')->once()->with('twitter::fallback_url')->andReturn('/');
        $url = $this->twitter->getFallbackUrl();
        $this->assertEquals('/', $url);
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
        $this->config->shouldReceive('get')->once()->with('twitter::callback_url')->andReturn('twitter/callback');
        $apiResponse = (object) array(
            'oauth_token' => 'bla',
            'oauth_token_secret' => 'bla',
            'httpstatus' => 200
        );
        $response = $this->twitter->authorize();
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $response);
    }
}