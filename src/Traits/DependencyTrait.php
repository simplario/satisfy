<?php

namespace Satisfy\Traits;

/**
 * Trait DependencyTrait
 *
 * @package Satisfy\Traits
 */
trait DependencyTrait
{
    /**
     * @var array
     */
    protected $role = [];

    /**
     * @var array
     */
    protected $env = [];

    /**
     * @return array
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @return array
     */
    public function getRole()
    {
        return $this->role;
    }


    /**
     * @param $env
     *
     * @return $this
     */
    public function env($env)
    {
        $this->env = (array)$env;

        return $this;
    }

    /**
     * @param $role
     *
     * @return $this
     */
    public function role($role)
    {
        $this->role = (array)$role;

        return $this;
    }

    /**
     * @param      $what
     * @param      $where
     * @param bool $all
     *
     * @return bool
     */
    protected function detectInArray($what, $where, $all = true)
    {
        $what = (array)$what;
        $where = (array)$where;
        $find = [];

        foreach ($what as $w) {
            if (in_array($w, $where, true)) {
                $find[] = $w;
            }
        }

        return $all ? count($what) === count($find) : count($find) > 0;
    }

    /**
     * @return bool
     */
    public function hasEmptyEnv()
    {
        return empty($this->env);
    }

    /**
     * @param $env
     *
     * @return bool
     */
    public function hasOneEnv($env)
    {
        return $this->detectInArray($env, $this->env, false);
    }

    /**
     * @param $env
     *
     * @return bool
     */
    public function hasAllEnv($env)
    {
        return $this->detectInArray($env, $this->env, true);
    }

    /**
     * @return bool
     */
    public function hasEmptyRole()
    {
        return empty($this->role);
    }

    /**
     * @param $role
     *
     * @return bool
     */
    public function hasOneRole($role)
    {
        return $this->detectInArray($role, $this->role, false);
    }

    /**
     * @param $role
     *
     * @return bool
     */
    public function hasAllRole($role)
    {
        return $this->detectInArray($role, $this->role, true);
    }
}