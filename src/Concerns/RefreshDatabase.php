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

use Fangx\Testing\RefreshDatabaseState;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\ApplicationContext;

trait RefreshDatabase
{
    public function refreshDatabase()
    {
        if (method_exists($this, 'getMigrationsPath')) {
            $path = $this->getMigrationsPath();
        } else {
            $path = 'migrations/testing';
        }

        if ($this->usingInMemoryDatabase()) {
            $this->command('migrate', [
                '--path' => $path,
            ]);
        } else {
            $this->refreshTestDatabase();
        }
    }

    public function beginDatabaseTransaction()
    {
        $database = ApplicationContext::getContainer()->get(Db::class);

        foreach ($this->connectionsToTransact() as $name) {
            $connection = $database->connection($name);
            $dispatcher = $connection->getEventDispatcher();

            $connection->unsetEventDispatcher();
            $connection->beginTransaction();
            $connection->setEventDispatcher($dispatcher);
        }

        $this->beforeContainerDestroyed(function () use ($database) {
            foreach ($this->connectionsToTransact() as $name) {
                $connection = $database->connection($name);
                $dispatcher = $connection->getEventDispatcher();

                $connection->unsetEventDispatcher();
                $connection->rollback();
                $connection->setEventDispatcher($dispatcher);
            }
        });
    }

    protected function usingInMemoryDatabase()
    {
        return strpos(getenv('DB_DATABASE'),':memory:') !== false;
    }

    protected function refreshTestDatabase()
    {
        if (method_exists($this, 'getMigrationsPath')) {
            $path = $this->getMigrationsPath();
        } else {
            $path = 'migrations/testing';
        }

        if (! RefreshDatabaseState::$migrated) {
            $this->command('migrate:fresh', [
                '--path' => $path,
            ]);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }

    /**
     * The database connections that should have transactions.
     *
     * @return array
     */
    protected function connectionsToTransact()
    {
        return property_exists($this, 'connectionsToTransact')
            ? $this->connectionsToTransact : [null];
    }

    /**
     * Determine if views should be dropped when refreshing the database.
     *
     * @return bool
     */
    protected function shouldDropViews()
    {
        return property_exists($this, 'dropViews')
            ? $this->dropViews : false;
    }
}
