<?php

namespace Satisfy\Recipe\Ubuntu1604;

use Satisfy\Recipe\AbstractRecipe;

/**
 * Class BootstrapRecipe
 * @package Satisfy\Recipe\Ubuntu1604
 */
class BootstrapRecipe extends AbstractRecipe {

    /**
     * @var string
     */
    protected $timezone = 'UTC';
    /**
     * @var string
     */
    protected $locale = 'en_US.UTF-8';
    /**
     * @var array
     */
    protected $packages = [];

    /**
     * @return $this|mixed
     * @throws \Exception
     */
    public function run()
    {
        $out = '';
        $out .= (new UpdateRecipe())->setHost($this->host)->run();
        $out .= $this->host->shell('sudo timedatectl set-timezone ' . $this->timezone);
        $out .= $this->host->shell('sudo locale-gen ' . $this->locale);
        $out .= (new PackagesRecipe(['packages' => $this->packages]))->setHost($this->host)->run();

        return $out;
    }


}