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
remove config/app.php
```
Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
Illuminate\Auth\AuthServiceProvider::class,
```
add config/app.php
```
Kbwebs\MultiAuth\AuthServiceProvider::class,
Kbwebs\MultiAuth\PasswordResets\PasswordResetServiceProvider::class,
Zizaco\Entrust\EntrustServiceProvider::class,
Loopeer\QuickCms\QuickCmsServiceProvider::class,
```
# Step4
init migrate and db seed command
```
php artisan quickcms:install
```
# Step5
publish js css image to your project/public dir
```
php artisan vendor:publish --tag=public --force
```
# Step6
test url
```
localhost:8000/admin
```
