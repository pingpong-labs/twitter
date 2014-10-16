<?php namespace Pingpong\Twitter\Traits;

trait HumanTrait {

    /**
     * @param $status
     * @param array $extra
     * @return mixed
     */
    public function tweet($status, array $extra = [])
    {
        $options = array_merge(compact('status'), $extra);

        return $this->post('statuses/update', $options);
    }

} 