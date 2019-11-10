<?php

namespace Satisfy\Recipe\Ubuntu1604;

use Satisfy\Recipe\AbstractRecipe;

/**
 * Class BootstrapRecipe
 *
 * @package Satisfy\Recipe\Ubuntu1604
 */
class BootstrapRecipe extends AbstractRecipe
{

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
    public function play()
    {
        return implode('', [
            UpdateRecipe::create([], $this->host)->play(),
            $this->host->shell('sudo timedatectl set-timezone ' . $this->timezone),
            $this->host->shell('sudo locale-gen ' . $this->locale),
            UpdateRecipe::create(['packages' => $this->packages], $this->host)->play(),
        ]);
    }


}