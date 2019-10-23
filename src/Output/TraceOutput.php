<?php

namespace Satisfy\Output;

/**
 * Class TraceOutput
 * @package Satisfy\Output
 */
class TraceOutput extends AbstractOutput
{
    /**
     * @param $string
     *
     * @return mixed|void
     */
    function write($string)
    {
        echo $string;
    }

}