<?php

namespace Satisfy\Recipe\Ubuntu1604;

use Satisfy\Recipe\AbstractRecipe;

/**
 * Class InstallDockerRecipe
 *
 * @package Satisfy\Recipe\Ubuntu1604
 */
class InstallDockerRecipe extends AbstractRecipe
{
    /**
     * @return mixed|string
     * @throws \Exception
     */
    public function play()
    {
        $out = '';
        $out .= $this->host->shell('docker --version || ( curl -fsSL https://get.docker.com | sh && docker --version )');
        $out .= $this->host->shell('docker-compose --version || ( sudo curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose && sudo chmod +x /usr/local/bin/docker-compose && docker-compose --version )');

        return $out;
    }
}