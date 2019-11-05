<?php

namespace Tests\Core;

use PHPUnit\Framework\TestCase;
use Satisfy\Host;
use Satisfy\Satisfy;
use Satisfy\Task;

/**
 * Class SatisfyTest
 * @package Tests\Core
 */
class SatisfyTest extends TestCase
{
    /**
     * @param $pack
     *
     * @return array
     */
    protected function getHostNames($pack)
    {
        $result = [];

        if (empty($pack)) {
            return $result;
        }
        /** @var Host $item */
        foreach ($pack as $item) {
            $result[] = $item->getName();
        }

        return $result;
    }


    /**
     * @throws \Exception
     */
    public function testSatisfyFilterPack()
    {
        $call = function () {};

        $satisfy = new Satisfy();
        $satisfy->host((new Host())->name('host-111'));
        $satisfy->task((new Task('task-111', $call)));

        $this->assertEquals([
            'task' => ['task-111' => []],
            'host' => ['host-111' => []],
        ], $satisfy->mapTaskToHost([]));

        // =================

        $satisfy = new Satisfy();
        $satisfy->host((new Host())->name('host-111')->tags(['web']));
        $satisfy->task((new Task('task-111', $call)));

        $this->assertEquals([
            'task' => ['task-111' => []],
            'host' => ['host-111' => ['web']],
        ], $satisfy->mapTaskToHost([]));



        // =================

        $satisfy = new Satisfy();
        $satisfy->host((new Host())->name('host-111')->tags(['web']));
        $satisfy->task((new Task('task-111', $call))->tags(['web']));

        $this->assertEquals([
            'task' => ['task-111' => ['web']],
            'host' => ['host-111' => ['web']],
        ], $satisfy->mapTaskToHost(['web']));



        // =================

        $satisfy = new Satisfy();
        $satisfy->host((new Host())->name('host-111')->tags(['web', 'app']));
        $satisfy->task((new Task('task-111', $call))->tags(['web']));

        $this->assertEquals([
            'task' => [],
            'host' => ['host-111' => ['web', 'app']],
        ], $satisfy->mapTaskToHost(['app']));


        // =================

        $satisfy = new Satisfy();
        $satisfy->host((new Host())->name('host-111')->tags(['web', 'app']));
        $satisfy->task((new Task('task-111', $call))->tags([]));

        $this->assertEquals([
            'task' => ['task-111' => []],
            'host' => ['host-111' => ['web', 'app']],
        ], $satisfy->mapTaskToHost(['app']));


        // =================

        $satisfy = new Satisfy();
        $satisfy->host((new Host())->name('host-111')->tags(['web', 'app']));
        $satisfy->task((new Task('task-111', $call))->tags(['production']));

        $this->assertEquals([
            'task' => ['task-111' => ['production']],
            'host' => ['host-111' => ['web', 'app']],
        ], $satisfy->mapTaskToHost(['app']));


//        $satisfy = new Satisfy();
//
//        $satisfy->host((new Host())->name('host-111')->tags([]));
//        $satisfy->host((new Host())->name('host-222')->tags(['web']));
//        $satisfy->task((new Task('task-1', $call)));
//        $satisfy->task((new Task('task-2', $call))->tags(['web']));
//
//        $this->assertEquals(['task-1' => ['host-111', 'host-222']], $satisfy->mapTaskToHost('task-1', []));
//        $this->assertEquals(['task-1' => ['host-222']], $satisfy->mapTaskToHost('task-1', ['web']));

        // $this->assertEquals(['task-0' => ['host-111']], $satisfy->mapTaskToHost('task-0', ['web', 'app', 'db']));
        // $this->assertEquals(['task-0' => ['host-111', 'host-222']], $satisfy->mapTaskToHost('task-0', ['web', 'app']));

        // print_r($satisfy->mapTaskToHost('task-1', ['web', 'app', 'db']));

        // $this->assertEquals(['task-1' => ['host-111']], $satisfy->mapTaskToHost('task-1', ['web', 'app', 'db']));
//        $this->assertEquals(['task-1' => ['host-111', 'host-222']], $satisfy->mapTaskToHost('task-0', ['web', 'app']));
//        $this->assertEquals(['task-1' => ['host-111', 'host-222', 'host-333']], $satisfy->mapTaskToHost('task-0', ['web']));
//        $this->assertEquals(['task-1' => ['host-111', 'host-222', 'host-333']], $satisfy->mapTaskToHost('task-0', []));


//        $satisfy->host((new Host())->name('production-1')->stage('production')->role(['front', 'back']));
//        $satisfy->host((new Host())->name('production-2')->stage('production')->role(['front']));
//        $satisfy->host((new Host())->name('production-3')->stage('production')->role(['back']));
//
//        $satisfy->host((new Host())->name('development-1')->stage('development')->role(['front', 'back']));
//        $satisfy->host((new Host())->name('development-2')->stage('development')->role(['front']));
//        $satisfy->host((new Host())->name('development-3')->stage('development')->role(['back']));
//
//        $satisfy->host((new Host())->name('development-1')->stage(['production', 'development'])->role(['front', 'back']));
//
//        $satisfy->task((new Task('task-0', function(){})));
//        $satisfy->task((new Task('task-1', function(){}))->stage('production')->role(['front']));
//        $satisfy->task((new Task('task-2', function(){}))->stage('development')->role(['front']));
//        $satisfy->task((new Task('task-3', function(){}))->stage(['production', 'development'])->role(['front']));
//        $satisfy->task((new Task('task-4', function(){}))->stage('production')->role(['front', 'back']));
//        $satisfy->task((new Task('task-5', function(){}))->stage('development')->role(['front', 'back']));
//        $satisfy->task((new Task('task-6', function(){}))->stage(['production', 'development'])->role(['front', 'back']));


    }


}