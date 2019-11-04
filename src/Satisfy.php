<?php

namespace Satisfy;

use Satisfy\Output\AbstractOutput;
use Satisfy\Output\NullOutput;
use Satisfy\Output\TraceOutput;
use Satisfy\Recipe\AbstractRecipe;
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
     * @var static
     */
    protected static $instance;

    /**
     * @return Satisfy
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

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
        $this->data[$key] = $value;

        return $this;
    }


    /**
     * @param          $items
     * @param callable $call
     *
     * @return array
     */
    public function each($items, callable $call)
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
    public function parallel($parallel = 1, array $items = [], callable $call)
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
    public function root()
    {
        return realpath(__DIR__ . '/../');
    }

    /**
     * @var Host[]
     */
    protected $hosts = [];

    /**
     * @var Task[]
     */
    protected $tasks = [];

    /**
     * @param Host|null $host
     *
     * @return Host|Host[]
     */
    public function host(Host $host = null)
    {
        if($host === null){
            return $this->hosts;
        }

        $this->hosts[] = $host;

        return $host;
    }

    /**
     * @param Task|null $task
     *
     * @return Task|Task[]
     */
    public function task(Task $task = null)
    {
        if($task === null){
            return $this->tasks;
        }

        $this->tasks[$task->name()] = $task;

        return $task;
    }


    /**
     * @param $command
     *
     * @return string
     * @throws \Exception
     */
    public function shell($command)
    {
        return $this->currentHost->shell($command);
    }

    /**
     * @param AbstractRecipe $recipe
     *
     * @return mixed
     */
    public function recipe(AbstractRecipe $recipe)
    {
        $recipe->setHost($this->currentHost);
        $output = $recipe->play();

        return $output;
    }


    /**
     * @var  Host
     */
    protected $currentHost;

    /**
     * @param $task
     * @param $stage
     * @param $roles
     *
     * @return $this
     */
    public function play($task, $stage, $roles)
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
        foreach ($this->hosts as $host) {
            if ($detect($stage, $host->stage()) && $detect($roles, $host->roles())) {
                $pack[] = $host;
            }
        }

        if(isset($this->tasks[$task]) && count($pack) > 0){

            // TODO make parallel

            foreach($pack as $host){
                $this->currentHost = $host;
                $this->tasks[$task]->play();
            }
        }

        return $this;
    }

    /**
     * @var AbstractOutput
     */
    protected $output;

    /**
     * @param $string
     *
     * @return $this
     * @throws \Exception
     */
    public function write($string)
    {
        if (!$this->output instanceof AbstractOutput) {
            if (PHP_SAPI === 'cli') {
                $this->output = new TraceOutput();
            } else {
                $this->output = new NullOutput();
            }
        }

        $this->output->write($string);

        return $this;
    }

    /**
     * @param $string
     *
     * @return Satisfy
     * @throws \Exception
     */
    public function writeln($string)
    {
        return $this->write($string . PHP_EOL);
    }

}
