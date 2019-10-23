<?php

namespace Satisfy\Output;

use Satisfy\Traits\SetOptionsTrait;

/**
 * Class AbstractOutput
 * @package Satisfy\Output
 */
abstract class AbstractOutput {

    use SetOptionsTrait;

    /**
     * AbstractOutput constructor.
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
     * @param $string
     *
     * @return mixed
     */
    abstract function write($string);

}