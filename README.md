![](http://7xpf31.com1.z0.glb.clouddn.com/quickcms2.gif)
# Installation
```
composer require "loopeer/quickcms:~2.0"
```
# Usage
update `config/app.php` add
```
//Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
//Illuminate\Auth\AuthServiceProvider::class,
Zizaco\Entrust\EntrustServiceProvider::class,
Kbwebs\MultiAuth\AuthServiceProvider::class,
Kbwebs\MultiAuth\PasswordResets\PasswordResetServiceProvider::class,
Loopeer\QuickCms\QuickCmsServiceProvider::class,
Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider::class,
Stevenyangecho\UEditor\UEditorServiceProvider::class,
Maatwebsite\Excel\ExcelServiceProvider::class,
```
in the `providers` array add
```
'Entrust' => Zizaco\Entrust\EntrustFacade::class,
'Excel' => Maatwebsite\Excel\Facades\Excel::class,
```
use `php artisan vendor:publish` and a `entrust.php` file will be created in app/config directory.
then update config/entrust.php
```
'role' => 'Loopeer\QuickCms\Models\Backend\Role',
'permission' => 'Loopeer\QuickCms\Models\Backend\Permission',
```
update config/auth.php
```
//'driver' => 'eloquent',
//'model' => App\User::class,
//'table' => 'users',
'multi-auth' => [
        'admin' => [
            'driver' => 'eloquent',
            'model'  => Loopeer\QuickCms\Models\Backend\User::class,
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
# Publish config resource
```
php artisan vendor:publish --force
```
# Document
[Doc](https://github.com/loopeer/quickcms/wiki)

# Artisan
```
php artisan quickcms:install
php artisan quickcms:create_backend_user
php artisan quickcms:init_permission 
```
