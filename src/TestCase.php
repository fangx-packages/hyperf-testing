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
use Hyperf\Contract\ApplicationInterface;
use Hyperf\Di\Container;
use Hyperf\Utils\ApplicationContext;
use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var Container
     */
    private $__container;

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
        if (!$this->__container) {
            $this->createContainer();
        }

        $this->refreshContainer();

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

    protected function refreshContainer()
    {
        $this->container = $this->__container;
    }

    protected function createContainer()
    {
        $this->__container = ApplicationContext::getContainer();
    }
}
