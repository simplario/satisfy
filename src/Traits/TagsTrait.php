<?php

namespace Satisfy\Traits;

/**
 * Trait TagTrait
 * @package Satisfy\Traits
 */
trait TagsTrait
{
    /**
     * @var array
     */
    protected $tags = [];

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param      $tags
     * @param bool $all
     *
     * @return bool
     */
    public function includeTags($tags, $all = true)
    {
        if(empty($this->tags)){
            return true;
        }


        $find = [];
        foreach ((array)$tags as $item) {
            if (in_array($item, $this->tags, true)) {
                $find[] = $item;
            }
        }

        return $all ? count($tags) === count($find) : count($find) > 0;
    }

    /**
     * @param $tags
     *
     * @return $this
     */
    public function tags($tags)
    {
        $this->tags = (array) $tags;

        return $this;
    }
}