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




$hosts = [
    [],
    ['web'],
    ['production'],
    ['stage'],
    ['app'],
    ['web', 'production'],
    ['app', 'stage'],
];

$tasks = [
    [],
    ['web'],
    ['production'],
    ['stage'],
    ['app'],
    ['web', 'production'],
    ['app', 'stage'],
];

$play = [
    [], // all to all
    ['web'],  // host include = web && task include = web
    ['production'],
    ['stage'],
    ['app'],
    ['web', 'production'],
    ['app', 'stage'],
];


// --env=stage         --role=nginx
// --env=production    --role=nginx

[ 'stage', 'production' ]



localhost()
    ->name('local-computer-111');

localhost()
    ->name('local-computer-222')
    ->stage('aaa')
    ->role(['aaa']);

localhost()
    ->name('local-computer-333')
    ->stage('aaa')
    ->role(['aaa', 'aaa2']);

localhost()
    ->name('local-computer-444')
    ->stage('bbb')
    ->role(['bbb', 'bbb2']);

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


task('ping', function () {
    shell('pwd');

    shell(' && ' , [
        'pwd'
    ]);

    shell(implode(' || ', [
        'pwd'
    ]));


    recipe('ping');

    // recipe(\Satisfy\Recipe\Ubuntu1604\PackagesRecipe::create(['packages' => ['htop']]));
});
//    ->stage([]);
//    ->roles(['all']);


//task('install-docker', function () {
//    shell('docker --version || curl -fsSL https://get.docker.com | sh');
//    shell('docker --version');
//    // shell('sudo docker run hello-world');
//    shell('docker-compose --version || ( sudo curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose && sudo chmod +x /usr/local/bin/docker-compose )');
//    shell('docker-compose --version');
////    // shell('cd /data/ && sudo docker-compose up -d');
//});
