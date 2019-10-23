<?php

namespace Satisfy\Provider;

use Satisfy\Host;
use Satisfy\Traits\RenderTrait;

/**
 * Class VagrantProvider
 * @package Satisfy\Provider
 */
class VagrantProvider extends AbstractProvider
{
    use RenderTrait;

    /**
     * @param array $options
     *
     * @return bool
     */
    public function exists(array $options = [])
    {
        $file = $options['dir'] . '/Vagrantfile';

        if (file_exists($file)) {
            return true;
        }

        return false;
    }


    /**
     * @param array $options
     *
     * @return null|Host
     * @throws \Exception
     */
    public function get(array $options = [])
    {
        if (!$this->exists($options)) {
            return null;
        }

        $host = new Host(['name' => $options['name']]);
        $host->setConnection(new \Satisfy\Connection(
            ['ssh' => ['host' => $options['params']['ip'],'port' => 22,'user' => 'vagrant','password' => 'vagrant']]
        ));

        return $host;
    }


    /**
     * @param array $options
     *
     * @return bool
     */
    public function create(array $options = [])
    {
        if ($this->exists($options)) {
            return true;
        }

        $file = $options['dir'] . '/Vagrantfile';
        $template = ROOT . '/template/vagrantfile.twig';

        @mkdir($options['dir'], 0777, true);
        $source = $this->render($template, $options['params'] ?? []);
        file_put_contents($file, $source);

        $this->execute("cd {$options['dir']} && vagrant up");

        return true;
    }

    /**
     * @param $options
     *
     * @return bool
     */
    public function destroy(array $options = [])
    {
        if (!$this->exists($options)) {
            return true;
        }

        $this->execute("cd {$options['dir']} && vagrant destroy -f");
        // shell_exec();

        $file = $options['dir'] . '/Vagrantfile';
        @unlink($file);

        return true;
    }

    /**
     * @param $command
     *
     * @return int
     */
    protected function execute($command)
    {
        fwrite(STDOUT, ' [runtime] ' . $command . PHP_EOL);

        $proc = proc_open($command, [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes);
        while (($line = fgets($pipes[1])) !== false) {
            fwrite(STDOUT, ' [runtime] ... ' . $line);
        }
        while (($line = fgets($pipes[2])) !== false) {
            fwrite(STDERR, ' [runtime] ...  ' . $line);
        }

        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        return proc_close($proc);
    }

}