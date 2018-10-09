<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-9-26
 * Time: 下午1:49
 */

namespace App\Process;


use EasySwoole\EasySwoole\Logger;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Swoole\Process\AbstractProcess;
use Swoole\Process;

class Inotify extends AbstractProcess
{

    public function run(Process $process)
    {
        // TODO: Implement run() method.
        if (extension_loaded('inotify')) {
            Logger::getInstance()->console('auto reload enable');
            // 监控应用目录，当应用目录有变动的时候，自动热重启。
            // 注意，并不是全部更改热重启都会生效，如：不受主进程管理（比如自定义进程），不在回调函数内的代码，比如在全局的Event中的代码。
            $a = function ($dir) use (&$a) {
                $data = array();
                if (is_dir($dir)) {
                    $data[] = $dir;
                    $files = array_diff(scandir($dir), array('.', '..'));
                    foreach ($files as $file) {
                        $data = array_merge($data, $a($dir.'/'.$file));
                    }
                } else {
                    $data[] = $dir;
                }
                return $data;
            };
            $list = $a(EASYSWOOLE_ROOT.'/App');
            $notify = inotify_init();
            // 为所有目录和文件添加inotify监听
            foreach ($list as $item) {
                inotify_add_watch($notify, $item, IN_CREATE | IN_DELETE | IN_MODIFY);
            }
            // 加入EventLoop
            swoole_event_add($notify, function () use ($notify) {
                $events = inotify_read($notify);
                if (!empty($events)) {
                    // 注意更新多文件的间隔时间处理，防止一次更新了10个文件，重启了10次，
                    Logger::getInstance()->console('service is going to reload');
                    ServerManager::getInstance()->getSwooleServer()->reload();
                }
            });
        }
    }

    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.
    }

    public function onReceive(string $str)
    {
        // TODO: Implement onReceive() method.
    }
}