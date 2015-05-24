<?php namespace Pingpong\Twitter\Traits;

trait GeoTrait
{

    /**
     * Search for places that can be attached to a statuses/update.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getGeoSearch(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('geo/search', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Locates places near the given coordinates which are similar in name.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getGeoSimilarPlaces(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('geo/similar_places', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Given a latitude and a longitude, searches for up to 20 places that can be used as a place_id when updating a status.
     * This request is an informative call and will deliver generalized results about geography.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getReserveGeoCode(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get('geo/reserve_geocode', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * As of December 2nd, 2013, this endpoint is deprecated and retired and no longer functions.
     * Place creation was used infrequently by third party applications and is generally no longer supported on Twitter.
     *
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function postGeoPlace(array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->post('geo/place', $parameters, $multipart, $appOnlyAuth);
    }

    /**
     * Returns all the information about a known place.
     *
     * @param $placeId
     * @param array $parameters
     * @param bool $multipart
     * @param bool $appOnlyAuth
     * @return mixed
     */
    public function getGeoId($placeId, array $parameters = array(), $multipart = false, $appOnlyAuth = false)
    {
        return $this->get("geo/id/{$placeId}", $parameters, $multipart, $appOnlyAuth);
    }
}
