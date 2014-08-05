<?php namespace Pingpong\Twitter\Traits;

/**
 * Class FavoritesTrait
 * @package Pingpong\Twitter\Traits
 */
trait FavoritesTrait {

    /**
     * Returns the 20 most recent Tweets favorited by the authenticating or specified user.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getFavoritesList(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('account/settings', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Un-favorites the status specified in the ID parameter as the authenticating user.
     * Returns the un-favorited status in the requested format when successful.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function postFavoritesDestroy(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->post('account/settings', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Favorites the status specified in the ID parameter as the authenticating user.
     * Returns the favorite status when successful.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function postFavoritesCreate(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->post('account/settings', $parameters, $multipart, $appOnlyAuth);
    }
} 