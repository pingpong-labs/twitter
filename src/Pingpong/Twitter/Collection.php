<?php namespace Pingpong\Twitter;

use Illuminate\Support\Collection as BaseCollection;

class Collection extends BaseCollection {

    /**
     * Returns only the data from the collection with the specified keys.
     *
     * @param  mixed|null|array $keys
     * @return array
     */
    public function only($keys)
    {
        return array_only($this->toArray(), $keys);
    }

}