<?php

namespace Satisfy\Traits;

/**
 * Trait RolesTrait
 * @package Satisfy\Traits
 */
trait RoleTrait
{
    /**
     * @var array
     */
    protected $role = ['default'];

    /**
     * @return array
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        foreach ((array)$role as $item) {
            if (in_array($item, (array)$this->role, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $role
     *
     * @return $this
     */
    public function role($role)
    {
        $this->role = (array) $role;

        return $this;
    }
}