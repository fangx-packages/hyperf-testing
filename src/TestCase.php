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

namespace Fangx\Testing;

use Carbon\Carbon;
use Hyperf\Di\Container;
use Hyperf\Di\Definition\DefinitionSourceFactory;
use Hyperf\Utils\ApplicationContext;
use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Container\ContainerInterface;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $afterContainerCreatedCallbacks = [];

    /**
     * @var array
     */
    protected $beforeContainerDestroyedCallbacks = [];

    /**
     * @var bool
     */
    protected $setUpHasRun = false;

    protected function setUp()
    {
        if (! $this->container) {
            $this->container = $this->createContainer();
            ApplicationContext::setContainer($this->container);
        }

        $this->setUpTraits();

        foreach ($this->afterContainerCreatedCallbacks as $callback) {
            call_user_func($callback);
        }

        $this->setUpHasRun = true;
    }

    protected function tearDown()
    {
        if ($this->container) {
            foreach ($this->beforeContainerDestroyedCallbacks as $callback) {
                call_user_func($callback);
            }

            $this->container = null;
            ApplicationContext::setContainer($this->createContainerMock());
            gc_collect_cycles();
        }

        $this->setUpHasRun = false;

        if (class_exists('Mockery')) {
            if ($container = Mockery::getContainer()) {
                $this->addToAssertionCount($container->mockery_getExpectationCount());
            }

            Mockery::close();
        }

        if (class_exists(Carbon::class)) {
            Carbon::setTestNow();
        }

        $this->afterContainerCreatedCallbacks = [];
        $this->beforeContainerDestroyedCallbacks = [];
    }

    public function afterContainerCreated(callable $callback)
    {
        $this->afterContainerCreatedCallbacks[] = $callback;

        if ($this->setUpHasRun) {
            call_user_func($callback);
        }
    }

    public function beforeContainerDestroyed(callable $callback)
    {
        $this->beforeContainerDestroyedCallbacks[] = $callback;
    }

    protected function setUpTraits()
    {
        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[\Fangx\Testing\Concerns\RefreshDatabase::class])) {
            $this->refreshDatabase();
        }

        if (isset($uses[\Fangx\Testing\Concerns\DatabaseMigrations::class])) {
            $this->runDatabaseMigrations();
        }

        if (isset($uses[\Fangx\Testing\Concerns\DatabaseTransactions::class])) {
            $this->beginDatabaseTransaction();
        }

        return $uses;
    }

    protected function createContainer()
    {
        return new Container((new DefinitionSourceFactory())());
    }

    protected function createContainerMock()
    {
        return new class() implements ContainerInterface {
            public function has($id)
            {
            }

            public function get($id)
            {
            }
        };
    }
}
