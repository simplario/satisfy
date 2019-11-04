<?php

namespace Satisfy;

use Satisfy\Output\AbstractOutput;
use Satisfy\Output\NullOutput;
use Satisfy\Output\TraceOutput;
use Satisfy\Recipe\AbstractRecipe;
use Satisfy\Traits\NameTrait;
use Satisfy\Traits\RenderTrait;
use Satisfy\Traits\SetOptionsTrait;

/**
 * Class Host
 * @package Satisfy
 */
class Host {

    use NameTrait;
    use SetOptionsTrait;
    use RenderTrait;


    protected $roles = [];
    protected $stage;
    protected $provider;

    /**
     * Host constructor.
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
     * @param       $provider
     * @param array $params
     *
     * @return $this
     */
    public function provider($provider, array $params = [])
    {
        $this->provider = ['name' => $provider, 'params' => $params];

        return $this;
    }

    /**
     * @param null $roles
     *
     * @return $this|array
     */
    public function roles($roles = null)
    {
        if ($roles === null) {
            return $this->roles;
        }

        $this->roles = (array)$roles;

        return $this;
    }

    /**
     * @param $stage
     *
     * @return $this
     */
    public function stage($stage = null)
    {
        if ($stage === null) {
            return $this->stage;
        }

        $this->stage = (array)$stage;

        return $this;
    }


    /**
     * @param array $options
     *
     * @return static
     * @throws \Exception
     */
    public static function create(array $options = [])
    {
        return new static($options);
    }

    /**
     * @var AbstractOutput
     */
    protected $output;

    /**
     * @param AbstractOutput $output
     *
     * @return $this
     */
    public function setOutput(AbstractOutput $output)
    {
        $this->output = $output;

        return $this;
    }

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
     * @return Host
     * @throws \Exception
     */
    public function writeln($string)
    {
        return $this->write($string . PHP_EOL);
    }

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @param Connection $connection
     *
     * @return $this
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
        $this->connection->setHost($this);

        return $this;
    }

    /**
     * @param $command
     *
     * @return null|string
     * @throws \Exception
     */
    public function shell($command)
    {
        $this->writeln($command);

        $out = $this->connection->shell($command);

        return $out;
    }

    /**
     * @param AbstractRecipe $recipe
     *
     * @return mixed
     */
    public function recipe(AbstractRecipe $recipe)
    {
        $recipe->setHost($this);
        $out = $recipe->run();

        return $out;
    }

    /**
     * @param string $from
     * @param string $to
     * @param array  $vars
     * @param bool   $append
     *
     * @return null|string
     * @throws \Exception
     */
    public function template($from, $to, array $vars = [], $append = false)
    {
        $source = $this->render($from, $vars);
        $action = $append ? '>>' : '>';
        $this->writeln('template ' . substr($from, 0,18) . ' ... ' . $to);
        $out = $this->connection->shell("echo '$source' {$action} $to");

        return $out;
    }


    /**
     * @var callable[]
     */
    protected $flow;

    /**
     * @param          $name
     * @param callable $func
     *
     * @return $this
     */
    public function flow($name, callable $func)
    {
        $this->flow[$name] = $func;

        return $this;
    }


    /**
     * @param $name
     *
     * @return $this
     * @throws \Exception
     */
    public function run($name)
    {
        $func = $this->flow[$name];

        $this->writeln("Start flow: '{$name}' on '{$this->getName()}'");

        $func($this);

        return $this;
    }
}