# Package/Uc

### 用户中心包， 封装用户登录/注册/忘记密码等相关功能模块

### TODO
+ [x] 自有账户登录相关实现
+ [ ] 短信/邮件的发送
+ [x] 第三方登录的相关实现

## Quick Start 

### Install
* 在`composer.json`上加上仓库配置
```
    // 增加仓库
    "repositories": {
        "package/uc": {
            "type": "git",
            "url": "git@16.163.83.112:package/uc.git"
        }
    }
    
    // 增加依赖
    "require": {
        "package/uc": "dev-master"
    }
```
* 安装依赖
```
    composer install
```

### Call In Project

#### 自有账户相关操作

```
// 获取缓存连接， 用户验证码
$cacheConn = \think\facade\Cache::instance();

$email = "12368723@qq.com";
$password = "1233213123123";

// 实例化客户端
$internalUcClient = new \Package\Uc\InternalClient\InternalClient(\Package\Uc\LoginType\LoginType::EMAIL, $cacheConn);


// 发送验证码
$internalUcClient->sendSmsCode(\Package\Uc\Impl\VerifyCodeImpl\VerifyCodeImpl::VERIFY_CODE_TYPE_REGISTER, $email);


// 根据验证码注册用户
$internalUcClient->register($email, $password, 'QGCMI0', ['nickname' => 'Hello World']);


// 用户名密码登录
$userInfo = $internalUcClient->login($email, $password);


// 登录token验证
$info = $internalUcClient->verifyToken($userInfo->jwt);


// 根据验证码修改密码
$internalUcClient->sendSmsCode(\Package\Uc\Impl\VerifyCodeImpl\VerifyCodeImpl::VERIFY_CODE_TYPE_FORGOT_PASSWORD,  $email);
$internalUcClient->changePassword($email, 'G3PWMO', $password.'123');


// 根据旧密码修改密码
$internalUcClient->changePasswordByOldPassword($email, $password.'123', $password.'123123');

```

#### 第三方登录相关操作
```
// 初始化客户端
$client = new OauthClient(
    (new OauthConfig())
        ->withLoginType(LoginType::FACEBOOK)
        ->withClientId('327489453789423')
        ->withClientSecret('687790709afbc789a0980f800af9')
        ->withRedirectURI('https://www.test.com')
);

// code 为客户端传过来的参数值
$client->login($code);

```

#### 其他配置项

* 自定义用户表名、数据库链接(默认表名为user， 连接为user_center)
  1. 自定义CustomerUser类， 继承自`Package\Uc\Model\User`
```
    <?php

    class CustomerUser extends \Package\Uc\Model\User
    {
    protected $table = 'xxxxx';
    protected $connection = 'xxxxxx';
    }
```
  
  2. 设置user类名
```
    Package\Uc\Config\Config::setConfig(Package\Uc\Config\ConfigOption::USER_MODEL_CLASS, CustomerUser::class);
```
  

* 自定义第三方登录表名、数据库连接(默认表名为oauth_user, 连接为user_center)
    1. 自定义CustomerOauthUser类， 继承自`Package\Uc\Model\OauthUser`
```
    <?php

    class CustomerOauthUser extends \Package\Uc\Model\OauthUser
    {
    protected $table = 'xxxxx';
    protected $connection = 'xxxxxx';
    }
```
  
    2. 设置OauthUser类名
```
    Package\Uc\Config\Config::setConfig(Package\Uc\Config\ConfigOption::OAUTH_USER_MODEL_CLASS, CustomerOauthUser::class);
```
  

* 自定义jwt token key
```
$internalUcClient = new \Package\Uc\InternalClient(LoginType::EMAIL, $dbConn, $cacheConn);
$internalUcClient->setJwtKey('the new key');
```

* 自定义token国企时间(默认为7天)
```
$internalUcClient = new \Package\Uc\InternalClient(LoginType::EMAIL, $dbConn, $cacheConn);
$internalUcClient->setTtl('123131');
```

* 自定义jwt 算法(默认为HS256)
```
$internalUcClient = new \Package\Uc\InternalClient(LoginType::EMAIL, $dbConn, $cacheConn);
$internalUcClient->setAlgo('HS384');
```


## 扩展维护

1. 扩展自有账户登录方式， 在`src/Impl/Internal`下新建`XxxxLoginImpl`, 实现接口`\Package\Uc\Interf\InternalLogin`
2. 扩展第三方登录方式， 在`src/Impl/Oauth`下新建`Xxxx`, 实现接口`\Package\Uc\Interf\OauthLogin`