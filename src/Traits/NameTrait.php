<?php

namespace Satisfy\Traits;

/**
 * Trait NameTrait
 *
 * @package Satisfy\Traits
 */
trait NameTrait
{

    protected $name = 'default';

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     *
     * @return $this|string
     */
    public function name($name = null)
    {
        $this->name = $name;

        return $this;
    }

}