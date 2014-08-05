<?php namespace Pingpong\Twitter\Traits;

/**
 * Class UsersTrait
 * @package Pingpong\Twitter\Traits
 */
trait UsersTrait {

    /**
     * Returns an HTTP 200 OK response code and a representation of the requesting user if authentication was successful.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getAccountVerifyCredentials(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->api('GET', 'account/verify_credentials', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Alias method for "getAccountVerifyCredentials".
     *
     * @param  array $parameters
     * @param bool $multipart
     * @param  boolean $appOnlyAuth
     * @return mixed
     */
    public function getCredentials(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->getAccountVerifyCredentials($parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns settings (including current trend, geo and sleep time information) for the authenticating user.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getAccountSettings(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('account/settings', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Updates the authenticating user's settings.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function postAccountSettings(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->post('account/settings', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns a variety of information about the user specified by the required user_id or screen_name parameter.
     * The author's most recent Tweet will be returned inline when possible.
     * GET users/lookup is used to retrieve a bulk collection of user objects.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getUsersShow(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('users/show', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Provides a simple, relevance-based search interface to public user accounts on Twitter.
     * Try querying by topical interest, full name, company name, location, or other criteria.
     * Exact match searches are not supported. Only the first 1,000 matching results are available.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getUsersSearch(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('users/search', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Report the specified user as a spam account to Twitter.
     * Additionally performs the equivalent of POST blocks/create on behalf of the authenticated user.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function postReportSpam(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->post('users/report_spam', $parameters, $multipart, $appOnlyAuth);
    }
}