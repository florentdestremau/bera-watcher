<?php

namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:florentdestremau/bera-watcher.git');

add('shared_files', ['.env.local']);
add('shared_dirs', ['var/log']);
add('writable_dirs', ['var/cache']);

// Hosts

host('bera.watch')
    ->set('remote_user', 'deployer')
    ->set('deploy_path', '~/bera-watcher')
    ->setRemoteUser('ubuntu');

// Hooks

after('deploy:failed', 'deploy:unlock');
