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
     * @param null $name
     *
     * @return $this|string
     */
    public function name($name = null)
    {
        if($name === null){
            return $this->name;
        }

        $this->name = $name;

        return $this;
    }

}