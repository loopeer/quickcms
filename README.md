# quickcms
laravel 5.1 quick cms

# Step1
```
composer require loopeer/quickcms
```
# Step2
update entrust config.php
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
Zizaco\Entrust\EntrustServiceProvider::class,
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
# Step4
publish js css image to your project/public dir
```
php artisan vendor:publish --tag=public --force
```
publish migrate
```
php artisan vendor:public --tag=migrations
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
