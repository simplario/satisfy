<?php

namespace Satisfy\Traits;

/**
 * Trait NameTrait
 * @package Satisfy\Traits
 */
trait NameTrait
{

    protected $name = 'default';

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

}