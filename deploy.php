<?php

namespace Deployer;

require 'recipe/symfony.php';
require 'contrib/crontab.php';

// Config

set('repository', 'git@github.com:florentdestremau/bera-watcher.git');

add('shared_files', ['.env.local']);
add('shared_dirs', ['var/log', 'var/db']);
add('writable_dirs', ['var/cache', 'var/db']);

set('deploy_path', '~/bera-watch');
set('domain', 'bera.watch');
set('public_path', 'public');
set('php_version', '8.2');
set('db_type', 'postgresql');
set('db_name', 'app');
set('db_user', 'app');
set('db_password', 'eSfK86aVUMJ4jg5XDk3NLv');
set('ssh_copy_id', '~/.ssh/id_ed25519.pub');

// Hosts

host('bera.watch')
    ->set('remote_user', 'deployer');


// dep provision -o remote_user=root

// Hooks

after('deploy:failed', 'deploy:unlock');

desc('Applies database migrations');
task('deploy:migrations', fn() => run('{{bin/php}} {{release_or_current_path}}/bin/console doctrine:migrations:migrate -n'));
before('deploy:publish', 'deploy:migrations');

after('deploy:success', 'crontab:sync');

add('crontab:jobs', [
    '*/15 * * * * cd {{current_path}} && {{bin/php}} bin/console app:extract-daily-beras >> /dev/null 2>&1',
]);
