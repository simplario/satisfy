<?php

namespace Satisfy\Provider;

/**
 * Class LocalhostProvider
 * @package Satisfy\Provider
 */
class LocalhostProvider extends AbstractProvider
{
    /**
     * @param array $options
     *
     * @return bool
     */
    public function exists(array $options = [])
    {
        return true;
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    public function create(array $options = [])
    {
        return true;
    }

    /**
     * @param $options
     *
     * @return bool
     */
    public function destroy(array $options = [])
    {
        return true;
    }
}