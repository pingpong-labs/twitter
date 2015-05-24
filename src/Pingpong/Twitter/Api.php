<?php namespace Pingpong\Twitter;

use Codebird\Codebird;

/**
 * Class Api
 * @package Pingpong\Twitter
 */
class Api extends Codebird
{

    /**
     * The Twitter Api Call.
     *
     * @param  string $method
     * @param  string $path
     * @param  array  $parameters
     * @param  bool   $multipart
     * @param  bool   $appOnlyAuth
     * @param  bool   $internal
     * @return mixed
     * @throws \Exception
     */
    public function api($method, $path, $parameters = array(), $multipart = false, $appOnlyAuth = false, $internal = false)
    {
        return $this->_callApi($method, $path, $parameters, $multipart, $appOnlyAuth, $internal);
    }
}
