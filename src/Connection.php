<?php

namespace Satisfy;

use Satisfy\Traits\SetOptionsTrait;

/**
 * Class Connection
 * @package Satisfy
 */
class Connection {

    use SetOptionsTrait;

    /**
     * @var array|null
     */
    protected $ssh;

    /**
     * @var Host
     */
    protected $host;

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
     * @param Host $host
     *
     * @return $this
     */
    public function setHost(Host $host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getSshSession()
    {
        if (!isset($this->ssh['session'])) {
            $this->ssh['session'] = ssh2_connect($this->ssh['host'], $this->ssh['port']);
            // $success = ssh2_keauth_($this->connection, $this->params['user'], $this->params['password']);
            $success = ssh2_auth_password($this->ssh['session'], $this->ssh['user'], $this->ssh['password']);

            if (!$success) {
                throw new \Exception('bad auth ...');
            }
        }

        return $this->ssh['session'];
    }

    /**
     * @param $command
     *
     * @return null|string
     * @throws \Exception
     */
    public function shell($command)
    {
        if ($this->ssh === null) {
            $output = $this->executeViaProc($command);
        } else {
            $output = $this->executeViaSsh($command);
        }

        return $output;
    }

    /**
     * @param $command
     *
     * @return string
     * @throws \Exception
     */
    protected function executeViaSsh($command)
    {
        $output = '';
        $stream = ssh2_exec($this->getSshSession(), $command);

        stream_set_blocking($stream, true);
        while ($message = fgets($stream)) {
            $this->host->write(' ... ' . $message);
            $output .= $message;
        }

        $this->host->writeln(' ... ');

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
            $this->host->write(' ... ' . $message);
            $output .= $message;
        }
        while (($message = fgets($pipes[2])) !== false) {
            // fwrite(STDERR, $message);
            $this->host->write(' ... ' . $message);
            $output .= $message;
        }

        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return proc_close($proc);
    }


}
