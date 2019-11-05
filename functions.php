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
 * @param array $options
 *
 * @return Host|Host[]
 * @throws Exception
 */
function localhost(array $options = [])
{
    $options['provider'] = 'localhost';

    return host($options);
}

/**
 * @param array $options
 *
 * @return Host|Host[]
 * @throws Exception
 */
function host(array $options = [])
{
    return satisfy()->host(new \Satisfy\Host($options));
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
 *
 * @return Satisfy
 */
function play($task, $stage, $roles)
{
    return satisfy()->play($task, $stage, $roles);
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