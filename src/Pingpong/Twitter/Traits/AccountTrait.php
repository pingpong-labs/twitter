<?php namespace Pingpong\Twitter\Traits;

trait AccountTrait {

    /**
     * @param array $options
     * @return mixed
     */
    public function getAccountVerifyCredentials(array $options = [])
    {
        return $this->get('account/verify_credentials', $options);
    }

}