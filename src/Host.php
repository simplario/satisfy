<?php

namespace Satisfy;

use Satisfy\Output\AbstractOutput;
use Satisfy\Output\NullOutput;
use Satisfy\Output\TraceOutput;
use Satisfy\Recipe\AbstractRecipe;
use Satisfy\Traits\NameTrait;
use Satisfy\Traits\RenderTrait;
use Satisfy\Traits\RoleTrait;
use Satisfy\Traits\SetOptionsTrait;
use Satisfy\Traits\StageTrait;
use Satisfy\Traits\TagsTrait;

/**
 * Class Host
 *
 * @package Satisfy
 */
class Host
{

    use NameTrait;
    use SetOptionsTrait;
    use RenderTrait;
    use RoleTrait;
    use StageTrait;
    use TagsTrait;

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
        Satisfy::getInstance()->write("[{$this->name}] " . $string);

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
        $this->writeln('template ' . substr($from, 0, 18) . ' ... ' . $to);
        $out = $this->shell("echo '$source' {$action} $to");

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

        $this->writeln("Start flow: '{$name}' on '{$this->name()}'");

        $func($this);

        return $this;
    }

    /**
     * @var array
     */
    protected $connect = [];

    /**
     * @param null $host
     *
     * @return $this|null
     */
    public function host($host = null)
    {
        if ($host === null) {
            return $host;
        }

        $this->connect['host'] = $host;

        return $this;
    }

    /**
     * @param null $user
     *
     * @return $this|null
     */
    public function user($user = null)
    {
        if ($user === null) {
            return $user;
        }

        $this->connect['user'] = $user;

        return $this;
    }

    /**
     * @param null $password
     *
     * @return $this|null
     */
    public function password($password = null)
    {
        if ($password === null) {
            return $password;
        }

        $this->connect['password'] = $password;

        return $this;
    }

    /**
     * @param null $port
     *
     * @return $this|null
     */
    public function port($port = null)
    {
        if ($port === null) {
            return $port;
        }

        $this->connect['port'] = $port;

        return $this;
    }

    // ssh ===============================


    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getSshSession()
    {
        if (isset($this->connect['host']) || isset($this->connect['port']) || isset($this->connect['user'])
            || isset($this->connect['password'])) {

            if (!isset($this->connect['@session'])) {
                $this->connect['@session'] = ssh2_connect($this->connect['host'], $this->connect['port']);
                // $success = ssh2_keauth_($this->connection, $this->params['user'], $this->params['password']);
                $success = ssh2_auth_password($this->connect['@session'], $this->connect['user'],
                    $this->connect['password']);

                if (!$success) {
                    throw new \Exception('bad ssh auth ...');
                }
            }

            return $this->connect['@session'];  // ssh session
        }

        return null; // localhost
    }


    /**
     * @param $command
     *
     * @return null|string
     * @throws \Exception
     */
    public function shell($command)
    {
        $this->writeln('>>> ' . $command);

        $sshSession = $this->getSshSession();

        if ($sshSession === null) {
            $output = $this->executeViaProc($command);
        } else {
            $output = $this->executeViaSsh($command, $sshSession);
        }

        return $output;
    }

    /**
     * @param $command
     * @param $sshSession
     *
     * @return string
     * @throws \Exception
     */
    protected function executeViaSsh($command, $sshSession)
    {
        $output = '';
        $stream = ssh2_exec($sshSession, $command);

        stream_set_blocking($stream, true);
        while ($message = fgets($stream)) {
            $this->write('... ' . $message);
            $output .= $message;
        }

        $this->writeln('... ');

        $output = rtrim($output,PHP_EOL);

        return $output;
    }


    /**
     * @param $command
     *
     * @return int
     * @throws \Exception
     */
    public function executeViaProc($command)
    {
        $output = '';
        $proc = proc_open($command, [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes);
        while (($message = fgets($pipes[1])) !== false) {
            // fwrite(STDOUT, $line);
            $this->write('... ' . $message);
            $output .= $message;
        }
        while (($message = fgets($pipes[2])) !== false) {
            // fwrite(STDERR, $message);
            $this->write('... ' . $message);
            $output .= $message;
        }

        $this->writeln('... ');

        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        proc_close($proc);

        $output = rtrim($output,PHP_EOL);

        return $output;
    }


}