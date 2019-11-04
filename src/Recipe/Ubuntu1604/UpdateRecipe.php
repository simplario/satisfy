<?php

namespace Satisfy\Recipe\Ubuntu1604;

use Satisfy\Recipe\AbstractRecipe;


/**
 * Class UpdateRecipe
 * @package Satisfy\Recipe\Ubuntu1604
 */
class UpdateRecipe extends AbstractRecipe
{

    /**
     * @return mixed|null|string
     * @throws \Exception
     */
    public function play()
    {
        return $this->host->shell('sudo apt-get update');
    }


}