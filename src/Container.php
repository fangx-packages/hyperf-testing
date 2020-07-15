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

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    public function get($id)
    {
        return null;
    }

    public function has($id)
    {
        return false;
    }

    public static function mock()
    {
        return new static();
    }
}
