<?php namespace Pingpong\Twitter\Traits;

/**
 * Class DirectMessagesTrait
 * @package Pingpong\Twitter\Traits
 */
trait DirectMessagesTrait {

    /**
     * Returns the 20 most recent direct messages sent to the authenticating user.
     * Includes detailed information about the sender and recipient user.
     * You can request up to 200 direct messages per call, up to a maximum of 800 incoming DMs
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     */
    public function getDirectMessages(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('direct_messages', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns the 20 most recent direct messages sent by the authenticating user.
     * Includes detailed information about the sender and recipient user.
     * You can request up to 200 direct messages per call, up toa maximum of 800 outgoing DMs.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     */
    public function getDirectMessagesSent(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {

        return $this->get('direct_messages/sent', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns a single direct message, specified by an id parameter.
     * Like the /1.1/direct_messages.format request, this method will include the user objects of the sender and recipient.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     */
    public function getDirectMessagesShow(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {

        return $this->get('direct_messages/show', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Destroys the direct message specified in the required ID parameter.
     * The authenticating user must be the recipient of the specified direct message.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     */
    public function postDirectMessagesDestroy(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->post('direct_messages/destroy', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Sends a new direct message to the specified user from the authenticating user.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     */
    public function postDirectMessagesNew(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->post('direct_messages/new', $parameters, $multipart, $appOnlyAuth);
    }
}