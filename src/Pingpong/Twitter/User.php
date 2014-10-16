<?php namespace Pingpong\Twitter;

use Pingpong\Twitter\Contracts\UserInterface;

class User implements UserInterface {

    /**
     * @var Collection
     */
    protected $data;

    /**
     * @param Collection $data
     */
    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->data->get('id');
    }

    /**
     * @return string
     */
    public function getIdStr()
    {
        return $this->data->get('id_str');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->data->get('name');
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->data->get('screen_name');
    }

}