<?php namespace Pingpong\Twitter\Traits;

/**
 * Class TrendsTrait
 * @package Pingpong\Twitter\Traits
 */
trait TrendsTrait {

    /**
     * Returns the top 10 trending topics for a specific WOEID, if trending information is available for it.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getTrandsPlace(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('trends/place', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns the locations that Twitter has trending topic information for.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getTrandsAvailable(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('trends/available', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns the locations that Twitter has trending topic information for, closest to a specified location.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getTrendsClosest(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('trends/closest', $parameters, $multipart, $appOnlyAuth);
    }
} 