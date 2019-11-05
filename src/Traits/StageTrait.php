<?php

namespace Satisfy\Traits;

/**
 * Trait StageTrait
 * @package Satisfy\Traits
 */
trait StageTrait
{
    /**
     * @var array
     */
    protected $stage = ['default'];

    /**
     * @return array
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * @param $stage
     *
     * @return bool
     */
    public function hasStage($stage)
    {
        foreach ((array)$stage as $item) {
            if (in_array($item, (array)$this->stage, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $stage
     *
     * @return $this
     */
    public function stage($stage)
    {
        $this->stage = (array) $stage;

        return $this;
    }
}