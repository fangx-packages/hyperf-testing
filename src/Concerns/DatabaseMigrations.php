<?php

declare(strict_types=1);

/**
 * Fangx's Packages
 *
 * @link     https://github.com/nfangxu/hyperf-testing
 * @document https://github.com/nfangxu/hyperf-testing/blob/master/README.md
 * @contact  nfangxu@gmail.com
 * @author   nfangxu
 */

namespace Fangx\Testing\Concerns;

trait DatabaseMigrations
{
    public function runDatabaseMigrations()
    {
        if (method_exists($this, 'getMigrationsPath')) {
            $path = $this->getMigrationsPath();
        } else {
            $path = 'migrations/testing';
        }

        $this->command('migrate:fresh', [
            '--path' => $path,
        ]);

        $this->beforeContainerDestroyed(function () use ($path) {
            $this->command('migrate:rollback', [
                '--path' => $path,
            ]);
        });
    }
}
