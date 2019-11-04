#!/usr/bin/php
<?php

require_once 'vendor/autoload.php';
require_once 'functions.php';

$book = isset($argv[1]) && is_file($argv[1]) ? $argv[1] : null;
$task = isset($argv[2]) ? $argv[2] : 'info';
$stage = isset($argv[3]) ? $argv[3] : null;
$roles = isset($argv[4]) ? (array) explode(',', $argv[4]) : null;

if ($book) {
    $paths[] = $book;
}
$paths[] = '../satisfy.php';
$paths[] = '../../satisfy.php';
$paths[] = '../../../satisfy.php';
$paths[] = '../../../../satisfy.php';
$paths[] = '../../../../../satisfy.php';

satisfy()->writeln('');

foreach ($paths as $path) {
    if (is_file($path)) {
        satisfy()->writeln('   File  : ' . $path);
        require_once $path;
        break;
    }
}

satisfy()->writeln('   Task  : ' . $task);
satisfy()->writeln('   Stage : ' . $stage);
satisfy()->writeln('   Roles : ' . implode(',', $roles));
satisfy()->writeln('');

play($task, $stage, $roles);