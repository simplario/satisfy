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
    protected static $data = [];

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function get($key, $default = null)
    {
        return isset(static::$data[$key]) ? static::$data[$key] : $default;
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        static::$data[$key] = $value;
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

    /**
     * @var Host[]
     */
    protected static $hosts = [];

    /**
     * @var Task[]
     */
    protected static $tasks = [];

    /**
     * @param Host|null $host
     *
     * @return Host|Host[]
     */
    public static function host(Host $host = null)
    {
        if($host === null){
            return static::$hosts;
        }

        static::$hosts[] = $host;

        return $host;
    }

    /**
     * @param Task|null $task
     *
     * @return Task|Task[]
     */
    public static function task(Task $task = null)
    {
        if($task === null){
            return static::$tasks;
        }

        static::$tasks[$task->getName()] = $task;

        return $task;
    }

    /**
     * @param $task
     * @param $stage
     * @param $roles
     */
    public static function run($task, $stage, $roles)
    {
        $detect = function ($what, $where) {
            foreach ((array)$what as $item) {
                if (in_array($item, (array)$where, true)) {
                    return true;
                }
            }

            return false;
        };

        $pack = [];
        /** @var Host $host */
        foreach (static::$hosts as $host) {
            if ($detect($stage, $host->stage()) && $detect($roles, $host->roles())) {
                $pack[] = $host;
            }
        }

        if(isset(static::$tasks[$task])){
            foreach($pack as $host){
                static::$tasks[$task]->run($host);
            }
        }
    }

}
