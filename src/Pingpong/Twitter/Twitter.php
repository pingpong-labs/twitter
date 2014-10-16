<?php namespace Pingpong\Twitter;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Routing\Redirector;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Pingpong\Twitter\Contracts\ApiInterface;
use Pingpong\Twitter\Exceptions\AuthenticationDeniedException;
use Pingpong\Twitter\Exceptions\AuthenticationFailedException;

class Twitter implements ApiInterface {

    // TODO : create trait for each twitter api 
    use Traits\AccountTrait,
        Traits\HumanTrait;

    /**
     * The twitter config array.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Base url.
     *
     * @var string
     */
    protected $baseUrl = 'https://api.twitter.com';

    /**
     * The api version want to use.
     *
     * @var string
     */
    protected $apiVersion = '1.1';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Oauth1
     */
    protected $oauth;

    /**
     * @var mixed
     */
    protected $response;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Routing\Redirector
     */
    protected $redirect;

    /**
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * The constructor.
     *
     * @param array $options
     * @param Redirector $redirect
     * @param Request $request
     * @param Store $session
     */
    public function __construct(array $options = [], Redirector $redirect, Request $request, Store $session)
    {
        $this->options = $options;
        $this->redirect = $redirect;
        $this->request = $request;
        $this->session = $session;

        $this->init();
    }

    /**
     * @return \Illuminate\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Routing\Redirector
     */
    public function getRedirector()
    {
        return $this->redirect;
    }

    /**
     * @param \Illuminate\Routing\Redirector $redirect
     * @return $this
     */
    public function setRedirector($redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * @return \Illuminate\Session\Store
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param \Illuminate\Session\Store $session
     * @return $this
     */
    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function option($key, $default = null)
    {
        return array_get($this->options, $key, $default);
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @param $apiVersion
     * @return $this
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * @param null $url
     * @return string
     */
    protected function getApiUrl($url = null)
    {
        return $this->baseUrl . '/' . $this->apiVersion . '/' . $url . '.json';
    }

    /**
     * Forget oauth token secret.
     *
     * @return void
     */
    protected function forgetOAuthToken()
    {
        $this->session->forget('oauth_token');
        $this->session->forget('oauth_token_secret');

        array_forget($this->options, ['token', 'token_secret']);
    }

    /**
     * @param null $url
     * @param string $authType
     * @return string
     */
    public function getAuthUrl($url = null, $authType = 'authenticate')
    {
        $this->logout();

        $this->forgetOAuthToken();

        $callbackUrl = $this->getCallbackUrl($url);

        $this->response = $this->client->post('oauth/request_token', ['body' => ['oauth_callback' => $callbackUrl]]);

        $params = (string)$this->response->getBody();

        parse_str($params);

        $this->session->put('oauth_token', $oauth_token);

        $this->session->put('oauth_token_secret', $oauth_token_secret);

        return "https://api.twitter.com/oauth/{$authType}?oauth_token={$oauth_token}";
    }

    /**
     * Get callback url.
     *
     * @param  string $default
     * @return void
     */
    public function getCallbackUrl($default = null)
    {
        return array_get($this->options, 'callback_url', $default);
    }

    /**
     * @param null $url
     * @return string
     */
    public function getAuthenticateUrl($url = null)
    {
        return $this->getAuthUrl($url);
    }

    /**
     * @param null $url
     * @return string
     */
    public function getAuthorizeUrl($url = null)
    {
        return $this->getAuthUrl($url, 'authorize');
    }

    /**
     * @param null $url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate($url = null)
    {
        return $this->redirect->to($this->getAuthenticateUrl($url));
    }

    /**
     * @param null $url
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authorize($url = null)
    {
        return $this->redirect->to($this->getAuthorizeUrl($url));
    }

    /**
     * @throws AuthenticationDeniedException
     * @throws AuthenticationFailedException
     * @return string
     */
    public function callback()
    {
        if ($this->request->get('denied'))
        {
            $this->forgetOAuthToken();

            throw new AuthenticationDeniedException("Authentication Denied");
        }
        elseif ( ! $this->request->has('oauth_token') || ! $this->request->has('oauth_verifier'))
        {
            $this->forgetOAuthToken();

            throw new \InvalidArgumentException("To perform the callback, the user must connected to twitter first.");
        }
        elseif ($this->session->get('oauth_token') == $this->request->get('oauth_token'))
        {
            return $this->doCallback();
        }
        else
        {
            $this->forgetOAuthToken();

            throw new AuthenticationFailedException("Authentication Failed", $this->response);
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToFallbackUrl()
    {
        return $this->redirect->to($this->getFallbackUrl());
    }

    /**
     * @return bool
     */
    protected function hasSessionOAuthToken()
    {
        return $this->session->has('oauth_token') && $this->session->has('oauth_token_secret');
    }

    /**
     * @return Oauth1
     */
    protected function getOAuth1()
    {
        if ($this->hasSessionOAuthToken())
        {
            $this->options = array_merge($this->options, [
                'token' => $this->session->get('oauth_token'),
                'token_secret' => $this->session->get('oauth_token_secret'),
            ]);
        }

        if ($this->check())
        {
            $this->options = array_merge($this->options, [
                'token' => $this->session->get('twitter.oauth_token'),
                'token_secret' => $this->session->get('twitter.oauth_token_secret'),
            ]);
        }

        return new Oauth1($this->options);
    }

    /**
     *
     */
    protected function refreshOAuth1()
    {
        $this->oauth = $this->getOAuth1();

        $this->attachOAuth1ToClient();
    }

    /**
     *
     */
    protected function init()
    {
        $this->client = $this->getHttpClient();

        $this->refreshOAuth1();
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Attach OAuth1 object to client.
     *
     * @return void
     */
    protected function attachOAuth1ToClient()
    {
        $this->client->getEmitter()->attach($this->oauth);
    }

    /**
     * Get fallback url. Used when the authentication has been denied from user.
     *
     * @return string
     */
    protected function getFallbackUrl()
    {
        return $this->option('fallback_url', $this->redirect->getUrlGenerator()->to('/'));
    }

    /**
     * Check the user status. Return true if the current user has been connected with twitter.
     *
     * @return bool
     */
    public function check()
    {
        $hasOauthToken = $this->session->has('twitter.oauth_token') && $this->session->has('twitter.oauth_token_secret');

        $hasUser = $this->session->has('twitter.user_id') && $this->session->has('twitter.screen_name');

        return $hasOauthToken && $hasUser;
    }

    /**
     * Logout the current user.
     *
     * @return void
     */
    public function logout()
    {
        $this->session->forget('twitter');
    }

    /**
     * Alias for "logout" method.
     *
     * @return void
     */
    public function destroy()
    {
        $this->logout();
    }

    /**
     * @param $url
     * @param array $options
     * @return \Pingpong\Twitter\Collection
     */
    public function post($url, array $options = [])
    {
        return $this->api('POST', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     * @return \Pingpong\Twitter\Collection
     */
    public function head($url, array $options = [])
    {
        return $this->api('HEAD', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     * @return \Pingpong\Twitter\Collection
     */
    public function get($url, array $options = [])
    {
        return $this->api('GET', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     * @return \Pingpong\Twitter\Collection
     */
    public function put($url, array $options = [])
    {
        return $this->api('PUT', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     * @return \Pingpong\Twitter\Collection
     */
    public function delete($url, array $options = [])
    {
        return $this->api('DELETE', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     * @return \Pingpong\Twitter\Collection
     */
    public function patch($url, array $options = [])
    {
        return $this->api('PATCH', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     * @return \Pingpong\Twitter\Collection
     */
    public function api($method, $url, array $options = [])
    {
        return new Collection($this->client->send($this->createRequest($method, $url, ['body' => $options]))->json());
    }

    /**
     * Create new client request.
     *
     * @param  string $method
     * @param  string $url
     * @param  array $options
     * @return mixed
     */
    public function createRequest($method, $url, array $options = [])
    {
        return $this->client->createRequest($method, $this->getApiUrl($url), $options);
    }

    /**
     * Get Logged user data.
     *
     * @param  array $options
     * @return User
     */
    public function getUser(array $options = [])
    {
        return new User($this->getAccountVerifyCredentials($options));
    }

    /**
     * @return Client
     */
    protected function getHttpClient()
    {
        return new Client(['base_url' => $this->baseUrl, 'defaults' => ['auth' => 'oauth']]);
    }

    /**
     * @return array
     */
    protected function doCallback()
    {
        $authVerifier = $this->request->get('oauth_verifier');

        $this->response = $this->client->post('oauth/access_token', ['body' => ['oauth_verifier' => $authVerifier]]);

        $body = (string)$this->response->getBody();

        parse_str($body);

        $data = [
            'oauth_token' => $oauth_token,
            'oauth_token_secret' => $oauth_token_secret,
            'user_id' => $user_id,
            'screen_name' => $screen_name,
        ];

        foreach ($data as $key => $value)
        {
            $this->session->put('twitter.' . $key, $value);
        }

        return $data;
    }

}