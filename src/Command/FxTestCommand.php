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

namespace Fangx\Testing\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Devtool\Generator\GeneratorCommand;
use Hyperf\Utils\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * @Command
 */
class FxTestCommand extends GeneratorCommand
{
    public function __construct()
    {
        parent::__construct('fx:test');
        $this->setDescription('Create a new test class');
    }

    public function configure()
    {
        parent::configure();
        $this->addOption('unit', 'u', InputOption::VALUE_NONE, 'Create a unit test.');
    }

    protected function isUnit()
    {
        return $this->input->getOption('unit');
    }

    protected function getStub(): string
    {
        if ($this->isUnit()) {
            return __DIR__ . '/stubs/unit-test.stub';
        }

        return __DIR__ . '/stubs/case-test.stub';
    }

    protected function getDefaultNamespace(): string
    {
        if ($this->isUnit()) {
            return 'HyperfTest\\Units';
        }
        return 'HyperfTest\\Cases';
    }

    protected function getPath($name)
    {
        return BASE_PATH . '/' . str_replace('\\', '/', Str::replaceFirst('HyperfTest', 'test', $name)) . '.php';
    }
}
