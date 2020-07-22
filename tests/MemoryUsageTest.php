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

namespace Fangx\Tests;

use Fangx\Testing\TestCase;
use Hyperf\Utils\ApplicationContext;

/**
 * @internal
 * @coversNothing
 */
class MemoryUsageTest extends TestCase
{
    public function testMemoryUsage()
    {
        $this->tearDown();
        $memory = memory_get_usage(true);
        for ($i = 0; $i < 10; ++$i) {
            $this->setUp();
            $this->container->set('test', rand(1, 999999));
            $this->assertTrue($memory === memory_get_usage(true));
            $this->tearDown();
        }
    }

    public function testContainerSet()
    {
        $this->assertTrue(! $this->container->has('test'));
    }

    public function testCoroutine()
    {
        // 当前测试修改了容器中的某些东西
        ApplicationContext::getContainer()->set('author', 'nfangxu');
        // 启动协程去处理其他东西
        go(function () {
            for ($i = 0; $i < 4; ++$i) {
                var_dump(ApplicationContext::getContainer()->has('author'));
                // 处理一些事情 消耗了 0.5 秒
                usleep(500 * 1000);
            }
        });
        // 当前测试干了其他事, 消耗了 1s
        sleep(1);
        // 测试结束, 执行了 tearDown()
        $this->tearDown();
        // 开启了一个新的测试
        $this->setUp();
        $this->assertTrue(true);
    }
}
