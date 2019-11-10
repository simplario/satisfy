<?php

/**
 * Install docker on production:backend
 *  - $ satisfy --task=bootstrap,docker --env=production --role=ALL
 *
 * Install docker:production:backend
 *  - $ satisfy --task=docker --env=production --role=backend
 *
 * Install docker on *:*:*
 *  - $ satisfy --task=ALL --env=ALL --role=ALL
 *
 * Install docker:production:backend
 *  - $ satisfy --task=deploy --env=production --role=ALL
 *
 */


localhost('local-111')
    ->env('local')
    ->role(['web']);
//
//localhost('local-222')
//    ->env('local')
//    ->role(['web', 'database']);
//
//localhost('local-333')
//    ->env('local')
//    ->role(['web', 'database']);
//
//localhost('local-444')
//    ->env('local')
//    ->role(['web', 'database']);



task('echo-status', function (\Satisfy\Host $host) {
    shell("echo '{$host->getName()} - echo-status'");
});



task('bootstrap', function (\Satisfy\Host $host) {
//    sleep($r = rand(1,5));
//    shell("echo {$host->getName()} {$r}");

    shell("echo {$host->getName()}");
    recipe('echo-status');
    recipe(\Satisfy\Recipe\Ubuntu1604\PackagesRecipe::create(['packages' => 'htop']));
    recipe(\Satisfy\Recipe\Ubuntu1604\InstallDockerRecipe::create());
})
    ->env(['local'])
    ->role([ 'web']);


task('install-docker', function () {
    shell('docker --version || curl -fsSL https://get.docker.com | sh');
    shell('docker --version');
    // shell('sudo docker run hello-world');
    shell('docker-compose --version || ( sudo curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose && sudo chmod +x /usr/local/bin/docker-compose )');
    shell('docker-compose --version');
//    // shell('cd /data/ && sudo docker-compose up -d');
});



//host()
//    ->name('test-vagrant')
//    ->host('192.168.50.21')
//    ->port('22')
//    ->user('vagrant')
//    ->password('vagrant')
//    ->provider('vagrant', [
//        'path'    => '@try/vagrants-1',
//        'cpu' => '1',
//        'memory' => '1024',
//        'name' => 'test1',
//        'hostname' => 'test1',
//        'ip' => '192.168.50.21',
//        'mount_from' => '.',
//        'mount_to' => '/data/',
//    ])
//    ->stage('-')
//    ->roles('-');