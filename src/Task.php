<?php

namespace Satisfy;

use Satisfy\Traits\DependencyTrait;
use Satisfy\Traits\NameTrait;

/**
 * Class Task
 *
 * @package Satisfy
 */
class Task
{
    use NameTrait;
    use DependencyTrait;

    /**
     * @var callable
     */
    protected $func;

    /**
     * Task constructor.
     *
     * @param          $name
     * @param callable $func
     */
    public function __construct($name, callable $func)
    {
        $this->name($name);
        $this->func = $func;
    }

    /**
     * @param Host $host
     *
     * @return $this
     */
    public function play(Host $host)
    {
        $f = $this->func;

        $f($host);

        return $this;
    }

}