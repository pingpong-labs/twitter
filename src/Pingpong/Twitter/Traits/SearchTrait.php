<?php namespace Pingpong\Twitter\Traits;

/**
 * Class SearchTrait
 * @package Pingpong\Twitter\Traits
 */
trait SearchTrait {

    /**
     * Returns a collection of relevant Tweets matching a specified query.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getSearchTweets(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('search/tweets', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns the authenticated user's saved search queries.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getSavedSearchesList(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('saved_searches/list', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Retrieve the information for the saved search represented by the given id. The authenticating user must be the owner of saved search ID being requested.
     *
     * @param $id
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getSavedSearchesShow($id, array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get("saved_searches/show/{$id}", $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Create a new saved search for the authenticated user. A user may only have 25 saved searches.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getSavedSearchesCreate(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->post('saved_searches/create', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Destroys a saved search for the authenticating user. The authenticating user must be the owner of saved search id being destroyed.
     *
     * @param $id
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getSavedSearchesDestroy($id, array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get("saved_searches/destroy/{$id}", $parameters, $multipart, $appOnlyAuth);
    }
} 