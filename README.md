# Package/Uc

### 用户中心包， 封装用户登录/注册/忘记密码等相关功能模块

### TODO
+ [x] 自有账户登录相关实现
+ [ ] 短信/邮件的发送
+ [ ] 第三方登录的相关实现

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
// 获取数据库和缓存连接
$dbConn = \think\facade\Db::connect();
$cacheConn = \think\facade\Cache::instance();

$email = "12368723@qq.com";
$password = "1233213123123";

// 实例化客户端
$internalUcClient = new \Package\Uc\InternalClient\InternalClient(\Package\Uc\LoginType\LoginType::EMAIL, $dbConn, $cacheConn);


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

#### 其他配置项
* 自定义用户表名(默认为user)
```
\Package\Uc\Constant::setUserDbTable('user_table');
```

* 自定义jwt token key
```
$internalUcClient = new \Package\Uc\InternalClient(LoginType::EMAIL, $dbConn, $cacheConn);
$internalUcClient->setJwyKey('the new key');
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

1. 扩展自有账户登录方式， 在`src/Impl`下新建`XxxxLogin`, 实现接口`\Package\Uc\Interf\InternalLogin`
2. 扩展第三方登录方式， 在`src/Impl`下新建`XxxxLogin`, 实现接口`\Package\Uc\Interf\ThirdLogin`