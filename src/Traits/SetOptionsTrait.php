<?php

namespace Satisfy\Traits;

/**
 * Trait SetOptionsTrait
 * @package Satisfy
 */
trait SetOptionsTrait
{

    /**
     * Set options
     *
     * @param array   $options   - options for setup
     * @param boolean $exception - trigger exception if can't setup property
     *
     * @return $this
     * @throws \Exception - when can't setup property
     */
    public function setOptions(array $options = [], $exception = true)
    {
        foreach ($options as $property => $value) {
            $method = 'set' . ucfirst($property);
            if (method_exists($this, $method)) {
                $this->$method($value);
            } elseif (property_exists($this, $property)) {
                $this->{$property} = $value;
            } elseif (method_exists($this, ($methodCamelize = 'set' . ucfirst($property)))) {
                $this->$methodCamelize($value);
            } elseif ($exception) {
                throw new \Exception(
                    "Can't find method '{$method}', '{$methodCamelize}' or property '{$property}' to setup it!"
                );
            }
        }

        return $this;
    }
}