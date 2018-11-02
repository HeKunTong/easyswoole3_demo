# REST 

easyswoole3.x新增REST类。

```php
<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/24
 * Time: 下午11:19
 */

namespace EasySwoole\Http\AbstractInterface;


use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

abstract class REST extends Controller
{
    /*
       *支持方法
        'GET',      // 从服务器取出资源（一项或多项）
       'POST',     // 在服务器新建一个资源
       'PUT',      // 在服务器更新资源（客户端提供改变后的完整资源）
       'PATCH',    // 在服务器更新资源（客户端提供改变的属性）
       'DELETE',   // 从服务器删除资源
       'HEAD',     // 获取 head 元数据
       'OPTIONS',  // 获取信息，关于资源的哪些属性是客户端可以改变的
     */
    function __hook(?string $actionName, Request $request, Response $response): void
    {
        $actionName = $request->getMethod().ucfirst($actionName);
        parent::__hook($actionName, $request, $response);
    }

    function index()
    {
        $this->actionNotFound('index');
    }
}
```  

hook方法将请求方式和请求路径重新包装了，其规则是请求方法 + 首字母大写的请求路径。

> 例子

```php
class User extends REST
{
    public function GETInfo()
    {
        $this->response()->write('info.....');
    }
}
```

在App\HttpController新建一个继承REST的User控制器，启动服务。  
在浏览器输入<label style="color:blue">http://localhost:9501/user/info</label>