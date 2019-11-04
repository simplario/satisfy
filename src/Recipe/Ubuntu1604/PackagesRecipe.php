<?php

namespace Satisfy\Recipe\Ubuntu1604;

use Satisfy\Recipe\AbstractRecipe;


/**
 * Class PackagesRecipe
 * @package Satisfy\Recipe\Ubuntu1604
 */
class PackagesRecipe extends AbstractRecipe {

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
        if (empty($this->packages)) {
            return '';
        }

        return $this->host->shell('sudo apt-get install -y ' . implode(' ', (array)$this->packages));
    }


}