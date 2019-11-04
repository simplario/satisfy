<?php

localhost()
    ->name('local-computer')
    ->stage('-')
    ->roles('-');

host()
    ->name('test-vagrant')
    ->host('192.168.50.21')
    ->port('22')
    ->user('vagrant')
    ->password('vagrant')
    ->provider('vagrant', [
        'path'    => '@try/vagrants-1',
        'cpu' => '1',
        'memory' => '1024',
        'name' => 'test1',
        'hostname' => 'test1',
        'ip' => '192.168.50.21',
        'mount_from' => '.',
        'mount_to' => '/data/',
    ])
    ->stage('-')
    ->roles('-');


task('ping', function () {
    shell('pwd');
    recipe(\Satisfy\Recipe\Ubuntu1604\PackagesRecipe::create(['packages' => ['htop']]));
});


task('install-docker', function () {
    shell('docker --version || curl -fsSL https://get.docker.com | sh');
    shell('docker --version');
    // shell('sudo docker run hello-world');
    shell('docker-compose --version || ( sudo curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose && sudo chmod +x /usr/local/bin/docker-compose )');
    shell('docker-compose --version');
//    // shell('cd /data/ && sudo docker-compose up -d');
});
