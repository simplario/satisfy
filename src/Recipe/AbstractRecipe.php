<?php

namespace Satisfy\Recipe;

use Satisfy\Host;
use Satisfy\Traits\RenderTrait;
use Satisfy\Traits\SetOptionsTrait;

/**
 * Class AbstractRecipe
 * @package Satisfy\Recipe
 */
abstract class AbstractRecipe
{

    use SetOptionsTrait;
    use RenderTrait;

    /**
     * @var Host
     */
    protected $host;

    /**
     * AbstractRecipe constructor.
     *
     * @param array $options
     *
     * @throws \Exception
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }


    /**
     * @param array $options
     *
     * @return AbstractRecipe
     * @throws \Exception
     */
    public static function create(array $options = [])
    {
        return new static($options);
    }


    /**
     * @param Host $host
     *
     * @return $this
     */
    public function setHost(Host $host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return mixed
     */
    abstract public function play();

}