<?php namespace Pingpong\Twitter;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Config\Repository;
use Illuminate\Routing\Redirector;
use Pingpong\Twitter\Traits\GeoTrait;
use Pingpong\Twitter\Traits\HelpTrait;
use Pingpong\Twitter\Traits\HumanTrait;
use Pingpong\Twitter\Traits\UsersTrait;
use Pingpong\Twitter\Traits\TrendsTrait;
use Pingpong\Twitter\Traits\TweetsTrait;
use Pingpong\Twitter\Traits\SearchTrait;
use Pingpong\Twitter\Traits\StatusesTrait;
use Pingpong\Twitter\Traits\FavoritesTrait;
use Pingpong\Twitter\Traits\DirectMessagesTrait;
use Pingpong\Twitter\Exceptions\TwitterApiException;
use Pingpong\Twitter\Traits\FriendsAndFollowersTrait;

/**
 * Class Twitter
 * @package Pingpong\Twitter
 */
class Twitter {

    use TweetsTrait, StatusesTrait, SearchTrait, DirectMessagesTrait, FriendsAndFollowersTrait, UsersTrait,
        FavoritesTrait, GeoTrait, TrendsTrait, HelpTrait, HumanTrait;

    /**
     * The package version.
     *
     * @var string
     */
    const VERSION = '2.0-dev';

    /**
     * The Base Twitter Api Instance.
     *
     * @var Api
     */
    protected $twitter;

    /**
     * The Laravel Config Repository.
     *
     * @var Repository
     */
    protected $config;

    /**
     * The Laravel Http Request.
     *
     * @var Request
     */
    protected $request;

    /**
     * The Laravel Session Store.
     *
     * @var Store
     */
    protected $session;

    /**
     * The Laravel Routing Redirector.
     *
     * @var Redirector
     */
    protected $redirect;

    /**
     * The Api Response.
     *
     * @var null
     */
    protected $response = null;

    /**
     * Return format.
     *
     * @var array
     */
    protected $format = [
        'OBJECT',
        'JSON',
        'ARRAY'
    ];

    /**
     * @var null|string
     */
    private $fallbackUrl;

    /**
     * The current consumer key.
     *
     * @var string
     */
    protected $consumerKey;

    /**
     * The current consumer secret.
     *
     * @var string
     */
    protected $consumerSecret;

    /**
     * The current oauth token.
     *
     * @var null|string
     */
    protected $oauthToken;

    /**
     * The current oauth token secret.
     *
     * @var null|string
     */
    protected $oauthTokenSecret;

    /**
     * The current bearer token.
     *
     * @var null|string
     */
    protected $bearerToken;

    /**
     * The current callback url.
     *
     * @var null|string
     */
    protected $callbackUrl;

    /**
     * The constructor.
     *
     * @param Api $twitter
     * @param Store $session
     * @param Repository $config
     * @param Request $request
     * @param Redirector $redirect
     * @param string $consumerKey
     * @param string $consumerSecret
     * @param string|null $oauthToken
     * @param string|null $oauthTokenSecret
     * @param string|null $bearerToken
     * @param string|null $callbackUrl
     * @param string|null $fallbackUrl
     */
    public function __construct(
        Api $twitter,
        Store $session,
        Repository $config,
        Request $request,
        Redirector $redirect,
        $consumerKey,
        $consumerSecret,
        $oauthToken = null,
        $oauthTokenSecret = null,
        $bearerToken = null,
        $callbackUrl = null,
        $fallbackUrl = null
    )
    {
        $this->twitter = $twitter;
        $this->session = $session;
        $this->config = $config;
        $this->request = $request;
        $this->redirect = $redirect;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->oauthToken = $oauthToken;
        $this->oauthTokenSecret = $oauthTokenSecret;
        $this->bearerToken = $bearerToken;
        $this->callbackUrl = $callbackUrl;
        $this->fallbackUrl = $fallbackUrl;

        $this->init($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret, $bearerToken);
    }


    /**
     * Initialize the oauth token api.
     *
     * @param $consumerKey
     * @param $consumerSecret
     * @param $oauthToken
     * @param $oauthTokenSecret
     * @param $bearerToken
     */
    public function init($consumerKey, $consumerSecret, $oauthToken, $oauthTokenSecret, $bearerToken)
    {
        $this->twitter->setConsumerKey($consumerKey, $consumerSecret);

        if ( ! is_null($bearerToken) )
        {
            $this->setBearerToken($bearerToken);
        }
        elseif ( ! is_null($oauthToken) && ! is_null($oauthTokenSecret) )
        {
            $this->setToken($oauthToken, $oauthTokenSecret);
        }
        elseif ($this->hasSessionToken())
        {
            $this->setSessionToken();
        }
    }

    /**
     * Set return format.
     *
     * @param $format
     */
    public function format($format)
    {
        if ( in_array($format, $this->format) )
        {
            $this->twitter->setReturnFormat($format);
        }
    }

    /**
     * Twitter Api Call.
     *
     * @param  string $method
     * @param  string $path
     * @param  array $parameters
     * @param  bool $multipart
     * @param  bool $appOnlyAuth
     * @param  bool $internal
     * @return mixed
     */
    public function api($method, $path, array $parameters = array(), $multipart = false, $appOnlyAuth = false, $internal = false)
    {
        return $this->twitter->api($method, $path, $parameters, $multipart, $appOnlyAuth, $internal);
    }

    /**
     * Helper method for making a GET request.
     *
     * @param  string $path
     * @param  array $parameters
     * @param  boolean $multipart
     * @param  boolean $appOnlyAuth
     * @param  boolean $internal
     * @return mixed
     */
    public function get($path, array $parameters = array(), $multipart = false, $appOnlyAuth = false, $internal = false)
    {
        return $this->api('GET', $path, $parameters, $multipart, $appOnlyAuth, $internal);
    }

    /**
     * Helper method for making a POST request.
     *
     * @param  string $path
     * @param  array $parameters
     * @param  boolean $multipart
     * @param  boolean $appOnlyAuth
     * @param  boolean $internal
     * @return mixed
     */
    public function post($path, array $parameters = array(), $multipart = false, $appOnlyAuth = false, $internal = false)
    {
        return $this->api('POST', $path, $parameters, $multipart, $appOnlyAuth, $internal);
    }

    /**
     * Helper method for making a PUT request.
     *
     * @param  string $path
     * @param  array $parameters
     * @param  boolean $multipart
     * @param  boolean $appOnlyAuth
     * @param  boolean $internal
     * @return mixed
     */
    public function put($path, array $parameters = array(), $multipart = false, $appOnlyAuth = false, $internal = false)
    {
        return $this->api('PUT', $path, $parameters, $multipart, $appOnlyAuth, $internal);
    }

    /**
     * Helper method for making a PATCH request.
     *
     * @param  string $path
     * @param  array $parameters
     * @param  boolean $multipart
     * @param  boolean $appOnlyAuth
     * @param  boolean $internal
     * @return mixed
     */
    public function patch($path, array $parameters = array(), $multipart = false, $appOnlyAuth = false, $internal = false)
    {
        return $this->api('PATCH', $path, $parameters, $multipart, $appOnlyAuth, $internal);
    }

    /**
     * Helper method for making a DELETE request.
     *
     * @param  string $path
     * @param  array $parameters
     * @param  boolean $multipart
     * @param  boolean $appOnlyAuth
     * @param  boolean $internal
     * @return mixed
     */
    public function delete($path, array $parameters = array(), $multipart = false, $appOnlyAuth = false, $internal = false)
    {
        return $this->api('DELETE', $path, $parameters, $multipart, $appOnlyAuth, $internal);
    }

    /**
     * Get callback url.
     *
     * @return mixed
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl ?: $this->config->get("twitter::callback_url");
    }

    /**
     * Get authorization url.
     *
     * @return string
     */
    public function getAuthorizeUrl()
    {
        return $this->twitter->oauth_authorize();
    }

    /**
     * Get authentication url.
     *
     * @return string
     */
    public function getAuthenticateUrl()
    {
        return $this->twitter->oauth_authenticate();
    }

    /**
     * Authorize the user.
     *
     * @param string|null $url
     * @throws Exceptions\TwitterApiException
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authorize($url = null)
    {
        return $this->doAuthFlow('authorize', $url, "Authorization failed.");
    }

    /**
     * Authenticate the user.
     *
     * @param string|null $url
     * @throws Exceptions\TwitterApiException
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate($url = null)
    {
        return $this->doAuthFlow('authenticate', $url, "Authentication failed.");
    }

    /**
     * Authenticates or authorizes to twitter.
     *
     * @param string $mode Either 'authenticate' or 'authorize'
     * @param string|null $url
     * @param $errorMessage
     * @throws Exceptions\TwitterApiException
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doAuthFlow($mode, $url = null, $errorMessage)
    {
        $callback = $url ?: $this->getCallbackUrl();

        $this->destroy();

        $this->twitter->setToken(null, null);

        $this->response = $this->api('POST', 'oauth/request_token', array('oauth_callback' => $callback));

        if ( $this->responseOk() )
        {
            $token = $this->response->oauth_token;
            $token_secret = $this->response->oauth_token_secret;

            $this->setToken($token, $token_secret);

            $this->session->put('oauth_request_token', $token);
            $this->session->put('oauth_request_token_secret', $token_secret);
            $this->session->put('oauth_request_verify', true);

            $method = 'get' . ucfirst($mode) . 'Url';
            $authUrl = $this->$method();

            return $this->redirect->to($authUrl);
        }

        $this->triggerError($errorMessage);
    }

    /**
     * Whether the http status code is 200.
     *
     * @return bool
     */
    protected function responseOk()
    {
        return $this->response ? $this->response->httpstatus == 200 : false;
    }

    /**
     * Whether the http status code is 200.
     *
     * @return bool
     */
    public function isOk()
    {
        return $this->responseOk();
    }

    /**
     * Perform callback.
     *
     * @param null $fallback_url
     * @throws Exceptions\TwitterApiException
     */
    public function callback($fallback_url = null)
    {
        if ( $this->request->has('denied') )
        {
            return $this->redirect->to($fallback_url ?: $this->getFallbackUrl());
        }
        elseif ( $this->request->has('oauth_verifier') && $this->session->has('oauth_request_verify') )
        {
            $this->twitter->setToken(
                $this->session->get('oauth_request_token'),
                $this->session->get('oauth_request_token_secret')
            );

            $this->session->forget('oauth_request_verify');

            $oauthVerifier = $this->request->get('oauth_verifier');

            $this->response = $this->twitter->api('POST', 'oauth/access_token', array('oauth_verifier' => $oauthVerifier));

            if ( $this->responseOk() )
            {
                $this->session->forget('oauth_request_token');
                $this->session->forget('oauth_request_token_secret');
                $this->session->forget('oauth_request_verify');

                $data = array('oauth_token', 'oauth_token_secret', 'user_id', 'screen_name');
                foreach ($data as $key)
                {
                    $this->session->put("twitter.{$key}", $this->response->{$key});
                }

                return $this->response;
            }
            $this->triggerError("Invalid or expired token.");
        }
        else
        {
            $this->triggerError("To perform callback, you must authorize the user first.");
        }
    }

    /**
     * Perform callback.
     *
     * @param null $fallback_url
     * @throws Exceptions\TwitterApiException
     */
    public function getCallback($fallback_url = null)
    {
        return $this->callback($fallback_url);
    }

    /**
     * Get the fallback url.
     *
     * @return string
     */
    public function getFallbackUrl()
    {
        return $this->fallbackUrl ?: $this->config->get("twitter::fallback_url");
    }

    /**
     * Trigger error with TwitterApiException.
     *
     * @param $message
     * @param null $response
     * @throws Exceptions\TwitterApiException
     */
    protected function triggerError($message, $response = null)
    {
        throw new TwitterApiException($message, $response);
    }

    /**
     * Determine whether have logged user.
     *
     * @return bool
     */
    public function check()
    {
        $hasUser = $this->session->has('twitter.user_id') && $this->session->has('twitter.screen_name');

        return $this->hasSessionToken() && $hasUser;
    }

    /**
     * Destroy user data.
     */
    public function destroy()
    {
        $this->session->forget('twitter');
    }

    /**
     * Logout the current user.
     *
     * @return bool
     */
    public function logout()
    {
        if ( $this->check() )
        {
            $this->destroy();

            return true;
        }

        return false;
    }

    /**
     * Determine whether the session token already stored.
     *
     * @return bool
     */
    protected function hasSessionToken()
    {
        return $this->session->has('twitter.oauth_token') && $this->session->has('twitter.oauth_token_secret');
    }

    /**
     * Set the token from session manager.
     */
    protected function setSessionToken()
    {
        $token = $this->session->get('twitter.oauth_token');
        $tokenSecret = $this->session->get('twitter.oauth_token_secret');

        $this->setToken($token, $tokenSecret);
    }

    /**
     * Set oauth token.
     *
     * @param $token
     * @param $tokenSecret
     * @return $this
     */
    public function setToken($token, $tokenSecret)
    {
        $this->twitter->setToken($token, $tokenSecret);

        return $this;
    }

    /**
     * Get bearer token.
     *
     * @return mixed
     * @throws Exceptions\TwitterApiException
     * @throws \Exception
     */
    public function getBearerToken()
    {
        $this->response = $this->twitter->oauth2_token();

        if ( $this->responseOk() )
        {
            return $this->response->access_token;
        }

        $this->triggerError("Unable to get bearer token.", $this->response);
    }

    /**
     * Set bearer token.
     *
     * @param $token
     * @return $this
     */
    public function setBearerToken($token)
    {
        $this->twitter->setBearerToken($token);

        return $this;
    }

    /**
     * Allows a Consumer application to exchange the OAuth Request Token for an OAuth Access Token with xAuth.
     *
     * @param $username
     * @param $password
     * @param string $mode
     * @return mixed
     */
    public function xAuth($username, $password, $mode = 'client_auth')
    {
        return $this->api('POST', 'oauth/access_token', array(
            'x_auth_username' => $username,
            'x_auth_password' => $password,
            'x_auth_mode' => $mode
        ));
    }

    /**
     * Set connection time out.
     *
     * @param $time
     * @return $this
     */
    public function setConnectionTimeout($time)
    {
        $this->twitter->setConnectionTimeout($time);

        return $this;
    }

    /**
     * Set request time out.
     *
     * @param $time
     * @return $this
     */
    public function setTimeout($time)
    {
        $this->twitter->setTimeout($time);

        return $this;
    }

    /**
     * Enable the cURL.
     *
     * @return $this
     */
    public function enableCurl()
    {
        $this->twitter->setUseCurl(true);

        return $this;
    }

    /**
     * Disable the cURL
     *
     * @return $this
     */
    public function disableCurl()
    {
        $this->twitter->setUseCurl(false);

        return $this;
    }
}