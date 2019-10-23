<?php

namespace Satisfy\Provider;

use Satisfy\Traits\SetOptionsTrait;

/**
 * Class AbstractProvider
 * @package Satisfy\Provider
 */
abstract class AbstractProvider {

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
     * @param array $options
     *
     * @return bool
     */
    abstract public function exists(array $options = []);

    /**
     * @param array $options
     *
     * @return bool
     */
    abstract public function create(array $options = []);

    /**
     * @param $options
     *
     * @return bool
     */
    abstract public function destroy(array $options = []);

}