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

use Hyperf\Di\Container;
use Hyperf\Utils\ApplicationContext;

class ContainerState
{
    /**
     * @var Container
     */
    private static $container;

    public static function get()
    {
        $c = static::$container ?: static::$container = static::_get();

        return \Opis\Closure\unserialize($c);
    }

    private static function _get()
    {
        return \Opis\Closure\serialize(ApplicationContext::getContainer());
    }
}
