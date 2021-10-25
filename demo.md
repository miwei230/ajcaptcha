### 范例

详情请查看test目录的PHP源码

#### 配置说明

```php
return [
    'font_file' => '', //自定义字体包路径， 不填使用默认值
    //文字验证码
    'click_world' => [
        'backgrounds' => [] 
    ],
    //滑动验证码
    'block_puzzle' => [
        'backgrounds' => [], //背景图片路径， 不填使用默认值
        'templates' => [], //模板图
        'offset' => 10, //容错偏移量
    ],
    //水印
    'watermark' => [
        'fontsize' => 12,
        'color' => '#ffffff',
        'text' => '我的水印'
    ],
    'cache' => [
        'constructor' => \Fastknife\Utils\CacheUtils::class,//若您使用了框架，不推荐使用该配置
        'method' => [
            //遵守PSR-16规范不需要设置此项目（tp6, laravel,hyperf）。如tp5就不支持（delete => rm）,
            'get' => 'get', //获取
            'set' => 'set', //设置
            'delete' => 'delete',//删除
            'has' => 'has' //key是否存在
        ]
    ]
];
```

##### 缓存配置

##### config.cache.constructor类型为string|array|function 使用以访问回调的方式获得缓存实例;

+ laravel 配置：

 ```
 'constructor' => [Illuminate\Support\Facades\Cache::class, 'store']
```

+ tp6(tp5.1) 配置

```php
  'constructor' => [think\Facade\Cache::class, 'instance']
```

> 无论配置写成`[think\Facade\Cache::class, 'instance']` 还是写成 `[think\Facade\Cache::class, 'store']` 目的都是为了获取缓存实例，具体情况视框架而定


       
+ 灵活自定义：
1. 如果您的需要使用类似以下命令打包配置文件（ThinkPHP,Laravel 命令）
    - php think optimize:config
    - php artisan optimize  
  则需要写成下面这样：
```php
    $instance = \think\facade\Cache::store();//获取缓存想实例
    //省略分部代码
    'constructor' => unserialize($instance);
```

因为在执行optimize打包命令时，会尝试将对象进行序列化。

2. 如果您不需要使用打包压缩命令，或者使用了像hyperf这样的框架，除了上述的写法，还可以写成这样：

```php
   'constructor' => function () {
            $container = \Hyperf\Utils\ApplicationContext::getContainer();
            //在构造函数中传入自已的配置
            return $container->get(\Psr\SimpleCache\CacheInterface::class);
    },
```

除此之处，您传入的缓存实例应遵守psr-16规范

#### 获取滑动验证码

```php
public function get(){
        $config = require '../src/config.php';
        $service = new BlockPuzzleCaptchaService($config);
        $data = $service->get();
        echo json_encode([
            'error' => false,
            'repCode' => '0000',
            'repData' => $data,
            'repMsg' => null,
            'success' => true,
        ]);
}
```

#### 滑动验证

```php
     public function check()
    {
        $config = require '../src/config.php';
        $service = new BlockPuzzleCaptchaService($config);
        $data = $_REQUEST;
        $msg = null;
        $error = false;
        $repCode = '0000';
        try {
            $service->check($data['token'], $data['pointJson']);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $error = true;
            $repCode = '6111';
        }
        echo json_encode([
            'error' => $error,
            'repCode' => $repCode,
            'repData' => null,
            'repMsg' => $msg,
            'success' => ! $error,
        ]);
    }
```

#### 获取文字验证码

```php
    public function get()
    {
        $config = require '../src/config.php';
        $service = new ClickWordCaptchaService($config);
        $data = $service->get();
        echo json_encode([
            'error' => false,
            'repCode' => '0000',
            'repData' => $data,
            'repMsg' => null,
            'success' => true,
        ]);
    }
```

#### 文字验证

```php
    public function check()
    {
        $config = require '../src/config.php';
        $service = new ClickWordCaptchaService($config);
        $data = $_REQUEST;
        $msg = null;
        $error = false;
        $repCode = '0000';
        try {
            $service->check($data['token'], $data['pointJson']);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $error = true;
            $repCode = '6111';
        }
        echo json_encode([
            'error' => $error,
            'repCode' => $repCode,
            'repData' => null,
            'repMsg' => $msg,
            'success' => ! $error,
        ]);
    }
```

#### 前端请示头修改示例

```javascript
import axios from 'axios';
import qs from 'qs';

axios.defaults.baseURL = 'https://captcha.anji-plus.com/captcha-api';

const service = axios.create({
    timeout: 40000,
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
    },
})
service.interceptors.request.use(
    config => {
        if (config.hasOwnProperty('data')) {
            config.data = qs.stringify(config.data)
        }
        return config
    },
    error => {
        Promise.reject(error)
    }
)
```

本包后续更新 ThinkPHP、Hyperf 等框架的demo，请持续关注
https://gitee.com/fastknife/aj-captcha