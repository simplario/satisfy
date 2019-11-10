<?php

use \Satisfy\Satisfy;
use \Satisfy\Host;

/**
 * @return Satisfy
 */
function satisfy()
{
    return \Satisfy\Satisfy::getInstance();
}


/**
 * @param       $name
 * @param array $options
 *
 * @return Host|Host[]
 * @throws Exception
 */
function localhost($name, array $options = [])
{
    $options['provider'] = 'localhost';

    return host($name, $options);
}

/**
 * @param       $name
 * @param array $options
 *
 * @return Host|Host[]
 * @throws Exception
 */
function host($name, array $options = [])
{
    return satisfy()->host(new \Satisfy\Host($name, $options));
}

/**
 * @param          $name
 * @param callable $callback
 *
 * @return \Satisfy\Task|\Satisfy\Task[]
 */
function task($name, callable $callback)
{
    return satisfy()->task(new \Satisfy\Task($name, $callback));
}

/**
 * @param $command
 *
 * @return string
 * @throws Exception
 */
function shell($command)
{
    return satisfy()->shell($command);
}


/**
 * @param $task
 * @param $stage
 * @param $roles
 * @param $parallel
 *
 * @return Satisfy
 * @throws Exception
 */
function play($task, $stage, $roles, $parallel)
{
    return satisfy()->play($task, $stage, $roles, $parallel);
}

/**
 * @param $recipe
 *
 * @return mixed
 */
function recipe($recipe)
{
    return satisfy()->recipe($recipe);
}

/**
 * @param $task
 * @param $stage
 * @param $roles
 */
function notify($task, $stage, $roles)
{
    // TODO
}