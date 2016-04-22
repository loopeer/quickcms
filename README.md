# QuickCMS
QuickCMS是基于laravel 5.1框架封装的一套快速开发库，包含entrust与multiauth结合的权限管理、菜单管理、用户管理等其他通用模块，页面模板使用了SmartAdmin。
快速开发功能核心类为GeneralController，QuickCms已封装的大部分模块都是通过配置文件实现的，不用写一行代码，只需简单配置一个文件即可实现业务模块的CURD功能（config/generals目录下的文件为系统模块的配置）。

![](http://7xpf31.com1.z0.glb.clouddn.com/quickcms2.gif)
# Step1
```
composer require loopeer/quickcms
```
# Step2
update `config/app.php` add
```
//Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
//Illuminate\Auth\AuthServiceProvider::class,
Zizaco\Entrust\EntrustServiceProvider::class,
Kbwebs\MultiAuth\AuthServiceProvider::class,
Kbwebs\MultiAuth\PasswordResets\PasswordResetServiceProvider::class,
Loopeer\QuickCms\QuickCmsServiceProvider::class,
Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider::class,
```
in the `providers` array add
```
'Entrust' => Zizaco\Entrust\EntrustFacade::class,
```
use `php artisan vendor:publish` and a `entrust.php` file will be created in app/config directory.
then update config/entrust.php
```
'role' => 'Loopeer\QuickCms\Models\Role',
'permission' => 'Loopeer\QuickCms\Models\Permission',
```
update config/auth.php
```
//'driver' => 'eloquent',
//'model' => App\User::class,
//'table' => 'users',
'multi-auth' => [
        'admin' => [
            'driver' => 'eloquent',
            'model'  => Loopeer\QuickCms\Models\User::class,
        ],
        'user' => [
            'driver' => 'eloquent',
            'model'  => Loopeer\QuickCms\Models\Account::class
        ]
    ],
```
update app/Http/Kernel.php,add protected $routeMiddleware []
```
'auth.admin' =>  \Loopeer\QuickCms\Http\Middleware\AdminMiddleware::class,
'auth.login' =>  \Loopeer\QuickCms\Http\Middleware\AdminAuthenticate::class,
'auth.permission' => \Loopeer\QuickCms\Http\Middleware\PermissionMiddleware::class,
```
# Step3
```
php artisan vendor:publish --force
php artisan migrate
php artisan quickcms:install
```
# Step4
```
localhost:8000/admin

# Doc
[Doc](https://github.com/loopeer/quickcms/wiki)

# Coding
[dengyongbin](https//github.com/dengyongbin)

[LilHorse](https://github.com/lilhorse)

[wangkaibo](https://github.com/wangkaibo)
