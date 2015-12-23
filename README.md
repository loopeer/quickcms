# quickcms
laravel 5.1 quick cms
![](http://7xpf31.com1.z0.glb.clouddn.com/quickcms-index.png)
![](http://7xpf31.com1.z0.glb.clouddn.com/quickcms-edit.png)
# Step1
```
composer require loopeer/quickcms
```
fix composer update error,add composer.json
```
"minimum-stability": "dev",
"prefer-stable" : true
```
then run `composer update`
# Step2
update `config/app.php` add
```
Zizaco\Entrust\EntrustServiceProvider::class,
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
# Step3
update config/app.php
```
//Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
//Illuminate\Auth\AuthServiceProvider::class,
Kbwebs\MultiAuth\AuthServiceProvider::class,
Kbwebs\MultiAuth\PasswordResets\PasswordResetServiceProvider::class,
Loopeer\QuickCms\QuickCmsServiceProvider::class,
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
# Step4
publish quickcms config file to config/quickcms.php
```
php artisan vendor:publish --tag=quickcms --force
```
publish js css image to your project/public dir
```
php artisan vendor:publish --tag=public --force
```
publish migrate
```
php artisan vendor:publish --tag=migrations
```
# Step5
init migrate and db seed command
```
php artisan migrate
php artisan quickcms:install
```
# Step6
test url
```
localhost:8000/admin
```
# Coding
[LilHorse](https://github.com/lilhorse)

[wangkaibo](https://github.com/wangkaibo)
