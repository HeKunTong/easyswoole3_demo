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