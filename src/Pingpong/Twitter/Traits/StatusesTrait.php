<?php namespace Pingpong\Twitter\Traits;

trait StatusesTrait {

    /**
     * Returns the 20 most recent mentions (tweets containing a users's @screen_name) for the authenticating user.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getStatusesMentionsTimeline(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('statuses/mentions_timeline', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns a collection of the most recent Tweets posted by the user indicated by the screen_name or user_id parameters.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getStatusesUserTimeline(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('statuses/user_timeline', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns a collection of the most recent Tweets and retweets posted by the authenticating user and the users they follow.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getStatusesHomeTimeline(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('statuses/home_timeline', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns the most recent tweets authored by the authenticating user that have been retweeted by others.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getStatusesRetweetsOfMe(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('statuses/retweets_of_me', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns fully-hydrated tweet objects for up to 100 tweets per request, as specified by comma-separated values passed to the id parameter.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getStatusesLookup(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('statuses/lookup', $parameters, $multipart, $appOnlyAuth);
    }

} 