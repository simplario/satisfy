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
     * @param Host $host
     *
     * @return Host|Host[]
     * @throws \Exception
     */
    public function host(Host $host)
    {
        if (isset($this->hosts[$host->getName()])) {
            throw new \Exception('Already exists host');
        }

        $this->hosts[$host->getName()] = $host;

        return $host;
    }

    /**
     * @param Task $task
     *
     * @return Task
     * @throws \Exception
     */
    public function task(Task $task)
    {
        if(isset($this->tasks[$task->getName()])){
            throw new \Exception('Already exists task');
        }

        $this->tasks[$task->getName()] = $task;

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
     * @param $recipe
     *
     * @return mixed
     */
    public function recipe($recipe)
    {
        if(!$recipe instanceof AbstractRecipe){

        }

        $recipe->setHost($this->currentHost);
        $output = $recipe->play();

        return $output;
    }


    /**
     * @var  Host
     */
    protected $currentHost;


    /**
     * @param       $name
     * @param array $tags
     *
     * @return array
     */
    public function mapTaskToHost(array $tags)
    {
        $pool = [
            'task' => [],
            'host' => [],
        ];

        foreach ($this->tasks as $taskName => $task) {
            if ($task->includeTags($tags)) {
                $pool['task'][$taskName] = $task->getTags();
            }
        }

        foreach ($this->hosts as $hostName => $host) {
            if ($host->includeTags($tags)) {
                $pool['host'][$hostName] = $host->getTags();
            }
        }

        return $pool;
    }


    /**
     * @param       $name
     * @param array $stage
     * @param array $role
     *
     * @return $this
     */
    public function play($name, array $stage, array $role)
    {
        if(!isset($this->tasks[$name])){
            echo 'task is undefined : ' . $name . PHP_EOL;
            return $this;
        }

        $task = $this->tasks[$name];

        $role[] = 'default';
        $stage[] = 'default';

        if(!$task->hasStage($stage)){
            echo 'task bad stage : ' . $name . PHP_EOL;
            return $this;
        }

//        if(!$task->hasRole($role)){
//            echo 'task bad role : ' . $name . PHP_EOL;
//            return $this;
//        }


        $hosts = [];
        foreach ($this->hosts as $host) {

            if (!$host->hasStage($stage)) {
                continue;
            }

//            if (!$host->hasRole($role)) {
//                continue;
//            }

            if (!$host->hasStage($task->getStage())) {
                continue;
            }

//            if (!$host->hasRole($task->getRole())) {
//                continue;
//            }

            $hosts[] = $host;
        }

        if(!count($hosts) > 0){
            echo 'hosts not found' . PHP_EOL;
            return $this;
        }

        foreach ($hosts as $host) {
            $this->currentHost = $host;
            $task->play();
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
