# think-template 

think-template 模板引擎

## 安装

安装es模板渲染库
```php
composer require easyswoole/template
```

安装think-template
```php
composer require topthink/think-template
```

## 实现think-template渲染


```php
<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19-8-30
 * Time: 上午10:59
 */

namespace App;


use EasySwoole\Template\RenderInterface;

class Template implements RenderInterface
{

    protected $template;

    function __construct()
    {
        $config = [
            'view_path'	=>	EASYSWOOLE_ROOT.'/Static/Template/',
            'cache_path'	=>	EASYSWOOLE_ROOT.'/Temp/cache_s/',
            'view_suffix'   =>	'html',
        ];
        $this->template = new \think\Template($config);
    }

    public function render(string $template, array $data = [], array $options = []): ?string
    {
        // TODO: Implement render() method.
        ob_start();
        $this->template->assign($data);
        $this->template->fetch($template);
        $content = ob_get_clean();
        return $content;
    }

    public function afterRender(?string $result, string $template, array $data = [], array $options = [])
    {
        // TODO: Implement afterRender() method.
    }

    public function onException(\Throwable $throwable): string
    {
        // TODO: Implement onException() method.
    }
}
```

## 注入模板驱动

```php
<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;

use App\Process\Inotify;
use App\Queue\Queue;
use App\Task\JdClient;
use App\Task\JdGoodClient;
use App\Template;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Component\Timer;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Template\Render;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');
        
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.

        Render::getInstance()->getConfig()->setRender(new Template());
        Render::getInstance()->attachServer(ServerManager::getInstance()->getSwooleServer());

        // 开启热重启进程
        // ServerManager::getInstance()->getSwooleServer()->addProcess((new Inotify('autoReload', ['disableInotify' => false]))->getProcess());

    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        $response->withHeader('Content-type','application/json;charset=utf-8');
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}

```

## 解析模板

```php
<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-18
 * Time: 上午11:32
 */

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Template\Render;

class Index extends Controller
{
    
    /**
     * 输出字符串
     */
    function index()
    {
        // TODO: Implement index() method.
        $this->response()->write('hello world');
    }

    function template() {
        $html = Render::getInstance()->render('index', [
            'name'=> 'easyswoole'
        ]);
        $this->response()->withHeader('Content-type', 'text/html');
        $this->response()->write($html);
    }

}
```