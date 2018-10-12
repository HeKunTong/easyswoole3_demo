# REST

  在easyswoole新增了REST用法，具体怎么用先看下底层这个类。
  
```
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
  
  在hook用法中，将当前的请求方式以及资源路径名重新组装了一下。
  
## 例子


```

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