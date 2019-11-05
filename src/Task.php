<?php

namespace Satisfy;

use Satisfy\Traits\NameTrait;
use Satisfy\Traits\RoleTrait;
use Satisfy\Traits\StageTrait;
use Satisfy\Traits\TagsTrait;


/**
 * Class Task
 *
 * @package Satisfy
 */
class Task
{
    use NameTrait;
    use RoleTrait;
    use StageTrait;
    use TagsTrait;

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
     * @return $this
     */
    public function play()
    {
        $f = $this->func;

        $f();

        return $this;
    }

}