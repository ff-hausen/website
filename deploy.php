<?php

namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:ff-hausen/website.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('ff-frankfurt-hausen.de')
    ->set('remote_user', 'ffhausen')
    ->set('deploy_path', '~/ff-frankfurt-hausen.de');

// Hooks

after('deploy:failed', 'deploy:unlock');

after('deploy:vendors', 'build');
desc('Builds frontend resources');
task('build', function () {
    cd('{{release_or_current_path}}');
    run('npm ci');
    run('npm run build');
});

desc('Stop the Inertia SSR server');
task('artisan:inertia:stop-ssr', function () {
    $artisan = '{{release_or_current_path}}/artisan';
    run("{{bin/php}} $artisan inertia:stop-ssr", no_throw: true);
});

after('artisan:migrate', 'daemon:restart');
desc('Restarts daemon processes');
task('daemon:restart', [
    'artisan:queue:restart',
    'artisan:inertia:stop-ssr',
]);
