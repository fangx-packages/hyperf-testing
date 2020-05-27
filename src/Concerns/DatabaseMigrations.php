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
        $this->command('migrate:fresh', [
            '--path' => 'migrations/testing',
        ]);

        $this->beforeContainerDestroyed(function () {
            $this->command('migrate:rollback', [
                '--path' => 'migrations/testing',
            ]);
        });
    }
}
