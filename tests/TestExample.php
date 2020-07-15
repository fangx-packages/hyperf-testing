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
class TestExample extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        defined('BASE_PATH') ?: define('BASE_PATH', __DIR__);
    }

    public function testInit()
    {
        Cache::$cache = md5(serialize(ApplicationContext::getContainer()));

        $this->assertTrue(true);
    }

    public function testReset()
    {
        $hash = md5(serialize(ApplicationContext::getContainer()));

        $this->assertEquals(Cache::$cache, $hash);
    }
}
