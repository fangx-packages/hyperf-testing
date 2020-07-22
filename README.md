# hyperf-testing

在 hyperf 中使用数据库进行单元测试. 

**推荐使用 [`fangx/sqlite-driver`](https://github.com/nfangxu/hyperf-sqlite-driver) 作为数据库驱动进行测试.**

> 为了保证测试独立, 所以每个测试完成后都会重新刷新容器.
> 该情况下会导致使用异步协程情况的时候, 使用 `ApplicationContext::getContainer()` 每次获取到的容器可能不一样
> 在异步里面应该避免直接使用 `ApplicationContext::getContainer()` 获取容器的相关内容.
> 官方推荐做法是 通过构造函数直接直接注入 `Container`, 而不是每次使用容器都通过 `ApplicationContext::getContainer()` 获取.

# 安装

```bash
composer require fangx/testing --dev
```

# 使用

> - `Fangx\Testing\Concerns\CommandCaller`: 提供 `command()` 方法, 在程序中执行命令.
> - `Fangx\Testing\Concerns\DatabaseMigrations`: 参照 laravel. 需要依赖 `CommandCaller` 执行迁移命令.
> - `Fangx\Testing\Concerns\RefreshDatabase`: 参照 laravel. 需要依赖 `CommandCaller` 执行迁移命令.
> - `Fangx\Testing\Concerns\DatabaseTransactions`: 参照 laravel. 

**运行迁移时, 默认执行的是 `migrations/testing` 目录下的迁移文件. 可以通过设置 `getMigrationsPath` 方法来自定义迁移文件的目录**

### 使用命令创建

```bash
php bin/hyperf.php fx:test UserTest
php bin/hyperf.php fx:test UserTest --unit
```

### 直接创建

在项目中的测试目录下创建 `Units/UserTest.php` 填入一下内容.

```php
<?php
declare(strict_types=1);

namespace HyperfTest\Units;

use Fangx\Testing\Concerns\CommandCaller;
use Fangx\Testing\TestCase;
use Fangx\Testing\Concerns\DatabaseMigrations;
use Fangx\Testing\Concerns\RefreshDatabase;

/**
 * @internal
 * @coversNothing
 */
class UserTest extends TestCase
{
    use CommandCaller;
    use DatabaseMigrations;
    
    public function testExample()
    {
        $this->assertTrue(true);
    }

}
```
