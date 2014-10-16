<?php namespace Pingpong\Twitter\Contracts;

interface UserInterface {

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getIdStr();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getUsername();

}