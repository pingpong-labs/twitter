<?php namespace Pingpong\Twitter;

use Closure;
use Exception;
use Codebird\Codebird as BaseTwitter;
use Illuminate\Foundation\Application;

/**
 * Class TwitterException
 * @package Pingpong\Twitter
 */
class TwitterException extends Exception {

}
;

/**
 * Class Twitter
 * @package Pingpong\Twitter
 */
class Twitter {

    /**
     * Application Object
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Config Repository Object
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * SessionManager Object
     *
     * @var \Illuminate\Session\SessionManager
     */
    protected $session;

    /**
     * Request Object
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Redirector Object
     *
     * @var \Illuminate\Routing\Redirector
     */
    protected $redirect;

    /**
     * Codebird\Codebird Object
     *
     * @var \Codebird\Codebird
     */
    protected $twitter;

    /**
     * Response Object
     *
     * @var null|object
     */
    protected $response = null;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config = $app['config'];
        $this->session = $app['session'];
        $this->request = $app['request'];
        $this->redirect = $app['redirect'];
        $this->twitter = BaseTwitter::getInstance();
        $this->prepare();
    }

    /**
     * Prepare auth
     *
     * @return void
     */
    protected function prepare()
    {
        $this->setConsumer();
        if ( $this->check() )
        {
            $this->setOAuthToken();
        }
    }

    /**
     * Set auth token from session
     *
     * @return self
     */
    protected function setOAuthToken()
    {
        $oauth_token = $this->session->get('oauth_token');
        $oauth_token_secret = $this->session->get('oauth_token_secret');
        $this->twitter->setToken($oauth_token, $oauth_token_secret);
        return $this;
    }

    /**
     * @param $oauth_token
     * @param $oauth_token_secret
     * @return $this
     */
    public function setNewOAuthToken($oauth_token, $oauth_token_secret)
    {
        //$oauth_token = $this->session->get('oauth_token');
        //$oauth_token_secret = $this->session->get('oauth_token_secret');
        $this->twitter->setToken($oauth_token, $oauth_token_secret);
        return $this;
    }

    /**
     * Set consumer key and consumer secret to the application
     *
     * @return self
     */
    protected function setConsumer()
    {
        $consumer_key = $this->getConfig('consumer_key');
        $consumer_secret = $this->getConfig('consumer_secret');
        $this->twitter->setConsumerKey($consumer_key, $consumer_secret);
        return $this;
    }

    /**
     * Set consumer key and consumer secret to the application
     *
     * @return self
     */
    public function reconfigureConsumer($key, $secret)
    {
        $consumer_key = $this->getConfig($key);
        $consumer_secret = $this->getConfig($secret);
        $this->twitter->setConsumerKey($consumer_key, $consumer_secret);
        return $this;
    }

    /**
     * Authorize to twitter
     *
     * @param  string $callback
     * @return Response
     */
    public function authorize($callback)
    {
        return $this->doAuthFlow('authorize', $callback);
    }

    /**
     * Authenticate to twitter
     *
     * @param  string $callback
     * @return Response
     */
    public function authenticate($callback)
    {
        return $this->doAuthFlow('authenticate', $callback);
    }

    /**
     * Authenticates or authorizes to twitter
     * @param  string $authMode Either 'authenticate' or 'authorize'
     * @param  string $callback
     * @return Response
     */
    protected function doAuthFlow($authMode, $callback)
    {
        $this->destroy();
        $request = $this->twitter->oauth_requestToken(array(
            'oauth_callback' => $callback
        ));
        $this->setResponse($request);
        if ( $this->isResponseOk($request) )
        {
            $this->storeNewSession($request);
            return $this->redirect->to($this->authUrl($authMode));
        }
        else
        {
            return $this->setResponse($request);
        }
    }

    /**
     * Set response to the application
     *
     * @param  object $response
     * @return Self
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Get current response from the application
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get boolean result, is response ok ?
     *
     * @return Boolean
     */
    public function isOk()
    {
        $response = $this->getResponse();
        if ( ! isset($response->httpstatus) )
        {
            return false;
        }
        return $this->isResponseOk($response);
    }

    /**
     * Get response and convert it to json.
     *
     * @return json
     */
    public function getResponseJson()
    {
        return json_encode($this->response);
    }

    /**
     * Get Authorization or Authentication URL form twitter
     *
     * @param  String $authMode 'authenticate' or 'authorize'
     * @return String
     */
    public function authUrl($authMode = 'authorize')
    {
        $functionName = $authMode . 'Url';
        return $this->{$functionName}();
    }

    /**
     * Get Authorization URL from twitter
     *
     * @return String
     */
    public function authorizeUrl()
    {
        return $this->twitter->oauth_authorize();
    }

    /**
     * Get Authorization URL from twitter
     *
     * @return String
     */
    public function authenticateUrl()
    {
        return $this->twitter->oauth_authenticate();
    }

    /**
     * Store new session token and secret to the application.
     *
     * @param  object $request
     * @return Self
     */
    public function storeNewSession($request)
    {
        $this->setToken($request->oauth_token, $request->oauth_token_secret);
        $this->session->put('oauth_verify', 1);
        return $this;
    }

    /**
     * Is response ok ?
     *
     * @param  object $request
     * @return boolean
     */
    protected function isResponseOk($request)
    {
        return $request->httpstatus == 200;
    }

    /**
     * Set new token to the session storage.
     *
     * @param  string $token
     * @param  string $secret
     * @return void
     */
    public function setToken($token, $secret)
    {
        $this->session->put('oauth_token', $token);
        $this->session->put('oauth_token_secret', $secret);
        return $this->twitter->setToken($token, $secret);
    }

    /**
     * Destroy the session oauth.
     *
     * @return boolean
     */
    public function destroy()
    {
        $this->forgetOAuthToken();
        $this->forgetOAuthVerify();
        return true;
    }

    /**
     * Forget current OAuth token and OAuth token secret.
     *
     * @return self
     */
    public function forgetOAuthToken()
    {
        $this->session->forget('oauth_token');
        $this->session->forget('oauth_token_secret');
        $this->setNewOAuthToken(null, null);
        return $this;
    }

    /**
     * Forget current OAuth verify.
     *
     * @return self
     */
    public function forgetOAuthVerify()
    {
        $this->session->forget('oauth_verify');
        return $this;
    }

    /**
     * Checking, is twitter connected ?
     *
     * @return boolean
     */
    public function check()
    {
        $checkToken = $this->session->has('oauth_token') && $this->session->has('oauth_token_secret');
        return $checkToken && $this->session->has('oauth_verify');
    }

    /**
     * Get configuration application.
     *
     * @param  string $key
     * @return void
     */
    public function getConfig($key)
    {
        return $this->config->get('twitter::twitter.' . $key);
    }

    /**
     * Get callback from authenticated user.
     *
     * @return int
     */
    public function getCallback()
    {
        if ( $this->request->has('denied') || ! $this->request->has('oauth_verifier') )
        {
            return 403;
        }
        $this->forgetOAuthVerify();
        $request = $this->twitter->oauth_accessToken(array(
            'oauth_verifier' => $this->request->get('oauth_verifier')
        ));

        if ( ! $this->isResponseOk($request) )
        {
            return 400;
        }

        $this->forgetOAuthToken()
            ->storeNewSession($request)
            ->setOAuthToken()
            ->setResponse($request);

        return 200;
    }

    /**
     * Send tweet.
     *
     * @param array $options
     * @return Self
     * @throws TwitterException
     */
    public function tweet($options = array())
    {
        if ( is_string($options) )
        {
            $params = array(
                'status' => $options
            );
        }
        elseif ( is_array($options) )
        {
            $params = $options;
        }
        else
        {
            throw new TwitterException("Parameter must be a string or array.");
        }
        $request = $this->twitter->statuses_update($params);
        return $this->setResponse($request);
    }

    /**
     * Upload media to twitter.
     *
     * @param  string $status
     * @param  string $media
     * @return void
     */
    public function upload($status, $media)
    {
        $request = $this->twitter->statuses_updateWithMedia(array(
            'status' => $status,
            'media[]' => $media
        ));

        return $this->setResponse($request);
    }

    /**
     * Search to twitter.
     *
     * @param  string $text
     * @param  boolean $option
     * @return void
     */
    public function search($text, $option = true)
    {
        $request = $this->twitter->search_tweets('q=' . $text, $option);

        return $this->setResponse($request);
    }

    /**
     * Get Codebird instance.
     *
     * @return Response
     */
    public function getInstance()
    {
        return $this->twitter;
    }

    /**
     * Get Home timeline.
     *
     * @return Response
     */
    public function getHomeTimeline(array $params = array())
    {
        return $this->twitter->statuses_homeTimeline($params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getUsers(array $params = array())
    {
        return $this->twitter->users_show($params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getUsersLookUp(array $params = array())
    {
        return $this->twitter->users_lookup($params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getTrendsPlace(array $params = array())
    {
        return $this->twitter->trends_place($params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getSearchTweets(array $params = array())
    {
        return $this->twitter->search_tweets($params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getFollowersList(array $params = array())
    {
        return $this->twitter->followers_list($params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getFriendsList(array $params = array())
    {
        return $this->twitter->friends_list($params);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     */
    public function retweet($id = 0)
    {
        return $this->twitter->__call('statuses_retweet_:id', $id);
    }

    /**
     * Get User timeline.
     *
     * @return Response
     */
    public function getUserTimeline(array $params = array())
    {
        return $this->twitter->statuses_userTimeline($params);
    }

    /**
     * Get Mentions timeline.
     *
     * @return Response
     */
    public function getMentionsTimeline(array $params = array())
    {
        return $this->twitter->statuses_mentionsTimeline($params);
    }

    /**
     * Get Retweet of me.
     *
     * @return Response
     */
    public function getRetweetOfMe(array $params = array())
    {
        return $this->twitter->statuses_retweetOfMe($params);
    }

    /**
     * Follow the specified id.
     *
     * @return Response
     */
    public function follow($params)
    {
        $parameters = $this->parseFollowParameter($params);
        return $this->twitter->friendships_create($parameters);
    }

    /**
     * Unfollow the specified user/id.
     *
     * @return Response
     */
    public function unfollow($params)
    {
        $parameters = $this->parseFollowParameter($params);
        return $this->twitter->friendships_destroy($parameters);
    }

    /**
     * Parse parameter for follow and unfollow method.
     *
     * @return Response
     */
    protected function parseFollowParameter($params)
    {
        //$parameter = []; // only for php 5.4+?
        $parameter = array();
        if ( is_string($params) )
        {
            $parameter['screen_name'] = $params;
        }
        elseif ( is_int($params) )
        {
            $parameter['id'] = $params;
        }
        else
        {
            $parameter = $params;
        }
        return $params;
    }

    /**
     * Get user credentials.
     *
     * @return Response
     */
    public function getCredentials()
    {
        return $this->twitter->account_verifyCredentials();
    }

    /**
     * Magic call to string.
     *
     * @return string|json|null|response
     */
    public function __toString()
    {
        return $this->getResponseJson();
    }
}