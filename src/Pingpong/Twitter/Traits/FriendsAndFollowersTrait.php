<?php namespace Pingpong\Twitter\Traits;

/**
 * Class FriendsAndFollowersTrait
 * @package Pingpong\Twitter\Traits
 */
trait FriendsAndFollowersTrait {

    /**
     * Returns a collection of user_ids that the currently authenticated user does not want to receive retweets from.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getFriendshipsNoRetweetsIds(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('friendships/no_retweets/ids', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns a cursored collection of user IDs for every user the specified user is following (otherwise known as their "friends").
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getFriendsIds(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('friends/ids', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns a collection of numeric IDs for every user who has a pending request to follow the authenticating user.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getFollowersIds(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('followers/ids', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns a collection of numeric IDs for every protected user for whom the authenticating user has a pending follow request.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getFriendshipsIncoming(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('friendships/incoming', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns a collection of numeric IDs for every protected user for whom the authenticating user has a pending follow request.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getFriendshipsOutgoing(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('friendships/outgoing', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Allows the authenticating users to follow the user specified in the ID parameter.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function postFriendshipsCreate(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->post('friendships/create', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Allows the authenticating user to unfollow the user specified in the ID parameter.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function postFriendshipsDestroy(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->post('friendships/destroy', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Allows one to enable or disable retweets and device notifications from the specified user.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function postFriendshipsUpdate(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->post('friendships/update', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns detailed information about the relationship between two arbitrary users.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getFriendshipsShow(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('friendships/show', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns a cursored collection of user objects for every user the specified user is following (otherwise known as their "friends").
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getFriendsList(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('friends/list', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns a cursored collection of user objects for users following the specified user.
     * At this time, results are ordered with the most recent following first — however,
     * this ordering is subject to unannounced change and eventual consistency issues.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getFollowersList(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('followers/lists', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns the relationships of the authenticating user to the comma-separated list of up to 100 screen_names or user_ids provided.
     * Values for connections can be: following, following_requested, followed_by, none, blocking, muting.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getFriendshipsLookup(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('friendships/lookup', $parameters, $multipart, $appOnlyAuth);
    }
} 