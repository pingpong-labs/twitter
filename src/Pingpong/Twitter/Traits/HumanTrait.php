<?php namespace Pingpong\Twitter\Traits;

trait HumanTrait
{

    /**
     * Allows the authenticating users to follow the user specified in the ID parameter.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function follow(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->postFriendshipsCreate($parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Allows the authenticating user to unfollow the user specified in the ID parameter.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function unfollow(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->postFriendshipsDestroy($parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Updates the authenticating user's current status and attaches media for upload.
     * In other words, it creates a Tweet with a picture attached.
     *
     * @param $status
     * @param $media
     * @param array $parameters
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function upload($status, $media, array $parameters = array(), $appOnlyAuth = false)
    {
        $data = array('status' => $status, 'media[]' => $media) + $parameters;

        return $this->postStatusesUpdateWithMedia($data, $appOnlyAuth);
    }

    /**
     * Updates the authenticating user's current status, also known as tweeting.
     *
     * @param $status
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function tweet($status, array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        $data = compact('status') + $parameters;

        return $this->postStatusesUpdate($data, $multipart, $appOnlyAuth);
    }
}
