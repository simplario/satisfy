<?php

namespace Satisfy;

use Satisfy\Traits\SetOptionsTrait;

/**
 * Class Satisfy
 * @package Satisfy
 */
class Satisfy
{

    use SetOptionsTrait;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->data[$key] = $key;

        return $this;
    }


    /**
     * @param          $items
     * @param callable $call
     *
     * @return array
     */
    public static function each($items, callable $call)
    {
        $result = [];
        foreach ($items as $index => $item) {
            $result[$index] = $call($index, $item);
        }

        return $result;
    }


    /**
     * @param int      $parallel
     * @param array    $items
     * @param callable $call
     *
     * @return bool
     */
    public static function parallel($parallel = 1, array $items = [], callable $call)
    {
        $keys = array_keys($items);
        $count = count($keys);
        $parallel = $count < $parallel ? $count : $parallel;


        if ($parallel <= 1 || $count <= 1) {
            foreach ($items as $index => $item) {
                $result[$index] = $call($index, $item);
            }

            return true;
        }

        $chunks = array_chunk($keys, $parallel);

        foreach ($chunks as $pack) {
            $pids = [];
            for ($i = 0; $i < $parallel; $i++) {

                if (!isset($pack[$i])) {
                    continue;
                }

                $pids[$i] = pcntl_fork();

                if (!$pids[$i]) {
                    $call($pack[$i], $items[$pack[$i]]);
                    exit(0);
                }
            }

            for ($i = 0; $i < $parallel - 1; $i++) {
                pcntl_waitpid($pids[$i], $status, WUNTRACED);
            }
        }

        return true;
    }

    /**
     * @return bool|string
     */
    public static function root()
    {
        return realpath(__DIR__ . '/../');
    }

}