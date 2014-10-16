<?php namespace Pingpong\Twitter\Contracts;

interface ApiInterface {

    /**
     * @param $method
     * @param $url
     * @param array $options
     * @return mixed
     */
    public function api($method, $url, array $options = []);

    /**
     * @param $url
     * @param array $options
     * @return mixed
     */
    public function get($url, array $options = []);

    /**
     * @param $url
     * @param array $options
     * @return mixed
     */
    public function post($url, array $options = []);

    /**
     * @param $url
     * @param array $options
     * @return mixed
     */
    public function head($url, array $options = []);

    /**
     * @param $url
     * @param array $options
     * @return mixed
     */
    public function put($url, array $options = []);

    /**
     * @param $url
     * @param array $options
     * @return mixed
     */
    public function patch($url, array $options = []);

    /**
     * @param $url
     * @param array $options
     * @return mixed
     */
    public function delete($url, array $options = []);

}