<?php

namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:florentdestremau/bera-watcher.git');

add('shared_files', ['.env.local']);
add('shared_dirs', ['var/log', 'var/db']);
add('writable_dirs', ['var/cache', 'var/db']);

set('deploy_path', '/var/www/bera-watcher');
// Hosts

host('bera.watch')
    ->set('remote_user', 'deployer')
    ->set('become', 'root')
    ->setRemoteUser('ubuntu');

// Hooks

after('deploy:failed', 'deploy:unlock');

desc('Applies database migrations');
task('deploy:migrations', fn() => run('{{bin/php}} {{release_or_current_path}}/bin/console doctrine:migrations:migrate -n'));
before('deploy:publish', 'deploy:migrations');
