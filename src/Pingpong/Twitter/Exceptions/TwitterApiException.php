<?php namespace Pingpong\Twitter\Exceptions;

class TwitterApiException extends \Exception
{
        
    /**
     * The response data.
     *
     * @var null|mixed
     */
    protected $response = null;

    /**
     * The constructor.
     * @param string 	 $message
     * @param mixed|null $response
     */
    public function __construct($message, $response = null)
    {
        $this->response = $response;

        parent::__construct($message);
    }

    /**
     * Get response.
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get error message.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->response ? $this->response->error : $this->getMessage();
    }
}
