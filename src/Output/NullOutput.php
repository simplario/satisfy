<?php

namespace Satisfy\Output;

/**
 * Class NullOutput
 * @package Satisfy\Output
 */
class NullOutput extends AbstractOutput
{
    /**
     * @param $string
     *
     * @return mixed|void
     */
    function write($string)
    {
        // nothing )))
    }

}