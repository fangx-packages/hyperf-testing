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

use Hyperf\Contract\ApplicationInterface;
use Hyperf\Utils\ApplicationContext;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

trait CommandCaller
{
    protected function command($command, $parameters = [])
    {
        $lastOutput = new NullOutput();

        $parameters['command'] = $command;

        $app = ApplicationContext::getContainer()
            ->get(ApplicationInterface::class);

        $app->setAutoExit(false);

        return $app->run(new ArrayInput($parameters), $lastOutput);
    }
}
