<?php namespace Pingpong\Twitter\Traits;

/**
 * Class TweetsTrait
 * @package Pingpong\Twitter\Traits
 */
trait TweetsTrait {

    /**
     * Returns a collection of the 100 most recent retweets of the tweet specified by the id parameter.
     *
     * @param  int|string $id
     * @param  array $parameters
     * @param  bool $multipart
     * @param  boolean $appOnlyAuth
     * @return mixed
     */
	public function getStatusesRetweets($id, array $parameters = array(), $multipart = false, $appOnlyAuth = false)
	{
		return $this->get("statuses/retweets/{$id}", $parameters, $multipart, $appOnlyAuth);
	}

    /**
     * Returns a single Tweet, specified by the id parameter. The Tweet's author will also be embedded within the tweet.
     *
     * @param  int|string $id
     * @param  array $parameters
     * @param bool $multipart
     * @param  boolean $appOnlyAuth
     * @return mixed
     */
	public function getStatusesShow($id, array $parameters = array(), $multipart = false, $appOnlyAuth = false)
	{
		return $this->get("statuses/show/{$id}", $parameters, $multipart, $appOnlyAuth);
	}

    /**
     * Destroys the status specified by the required ID parameter. The authenticating user must be the author of the specified status.
     * Returns the destroyed status if successful.
     *
     * @param  int|string $id
     * @param  array $parameters
     * @param bool $multipart
     * @param  boolean $appOnlyAuth
     * @return mixed
     */
	public function postStatusesDestroy($id, array $parameters = array(), $multipart = false, $appOnlyAuth = false)
	{
		return $this->post("statuses/destroy/{$id}", $parameters, $multipart, $appOnlyAuth);
	}

    /**
     * Updates the authenticating user's current status, also known as tweeting.
     *
     * @param  array $parameters
     * @param bool $multipart
     * @param  boolean $appOnlyAuth
     * @return mixed
     */
	public function postStatusesUpdate(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
	{
		return $this->post("statuses/update", $parameters, $multipart, $appOnlyAuth);
	}

    /**
     * Retweets a tweet. Returns the original tweet with retweet details embedded.
     *
     * @param  int|string $id
     * @param  array $parameters
     * @param  bool $multipart
     * @param  boolean $appOnlyAuth
     * @return mixed
     */
	public function postStatusesRetweet($id, array $parameters = array(), $multipart = false, $appOnlyAuth = false)
	{
		return $this->post("statuses/retweet/{$id}", $parameters, $multipart, $appOnlyAuth);
	}

	/**
	 * Updates the authenticating user's current status and attaches media for upload.
	 * In other words, it creates a Tweet with a picture attached.
	 * 
	 * @param  array   $parameters  
	 * @param  boolean $appOnlyAuth 
	 * @return mixed               
	 */
	public function postStatusesUpdateWithMedia(array $parameters = array(), $appOnlyAuth = false)
	{
		return $this->post("statuses/update_with_media", $parameters, $appOnlyAuth, true, $appOnlyAuth);
	}	

	/**
	 * Returns information allowing the creation of an embedded representation of a Tweet on third party sites.
	 * 
	 * @param  array   $parameters  
	 * @param  boolean $multipart   
	 * @param  boolean $appOnlyAuth 
	 * @return mixed               
	 */
	public function getStatusesOembed(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
	{
		return $this->get('statuses/oembed', $parameters, $multipart, $appOnlyAuth);
	}

}