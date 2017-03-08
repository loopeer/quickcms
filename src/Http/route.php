<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/11/4
 * Time: 下午6:01
 */

Event::listen('illuminate.query', function($query, $params, $time, $conn) {
   if(config('quickCms.sql_log_switch')) {
      $logger = \Loopeer\QuickCms\Services\Utils\LogUtil::getLogger('sql', 'sql');
      $logger->addInfo($query . '   params = ' . implode(',', $params) . '  time = ' . $time . '   conn = ' . $conn);
   }
});


Route::group(array('namespace' => 'Backend'), function () {
    Route::get('admin/login', 'IndexController@getLogin');
    Route::post('admin/login',array('middleware' => 'auth.login','as' => 'admin.login','uses' => 'IndexController@postLogin'));

    Route::get('admin/permissions/search', ['as' => 'admin.permissions.search', 'uses' => 'PermissionController@search']);
    Route::get('admin/selector/search', ['as' => 'admin.selector.search', 'uses' => 'SelectorController@search']);
    Route::get('admin/pushes/search', ['as' => 'admin.pushes.search', 'uses' => 'PushesController@search']);
    Route::get('admin/permissions/{id}/searchPermission', 'OperationPermissionController@search');
});

Route::group(array('prefix' => 'admin', 'middleware' => 'auth.admin', 'namespace' => 'Backend'), function () {
   Route::get('/', 'IndexController@getIndex');
   Route::get('logout',array('as' => 'admin.logout','uses' => 'IndexController@logout'));
   Route::get('index', 'IndexController@index');

   // 图片上传
   Route::post('blueimp', array('as'=>'admin.blueimp.upload', 'uses'=>'BlueimpController@upload'));
   Route::get('blueimp/{id}', array('as'=>'admin.blueimp.delete', 'uses'=>'BlueimpController@destroy'));
   Route::get('blueimp', array('as'=>'admin.blueimp.index', 'uses'=>'BlueimpController@getImage'));

   //文件上传
   Route::post('dropzone/upload', 'DropzoneController@upload');
   Route::get('dropzone/fileList/{id}', 'DropzoneController@fileList');

   Route::get('permissions/delete/{id}',array('as'=>'admin.permissions.delete','uses'=>'PermissionController@delete'));
   Route::post('permissions/update/{id}',array('as'=>'admin.permissions.update','uses'=>'PermissionController@update'));
   Route::get('permissions/{id}/indexPermission', 'OperationPermissionController@index');
   Route::get('permissions/{id}/createPermission', 'OperationPermissionController@create');
   Route::get('permissions/{id}/editPermission/{permission_id}', 'OperationPermissionController@edit');
   Route::post('permissions/{id}/deletePermission/{permission_id}', 'OperationPermissionController@destroy');
   Route::post('permissions/{id}/storePermission', 'OperationPermissionController@store');
   Route::get('permissions/{id}/initPermission', 'OperationPermissionController@init');
   Route::resource('permissions', 'PermissionController');

   Route::resource('feedbacks', 'FastController', ['model' => \Loopeer\QuickCms\Models\Backend\Feedback::class]);
   Route::resource('versions', 'FastController', ['model' => \Loopeer\QuickCms\Models\Backend\Version::class]);
   Route::resource('systems', 'FastController', ['model' => \Loopeer\QuickCms\Models\Backend\System::class]);
   Route::resource('documents', 'FastController', ['model' => \Loopeer\QuickCms\Models\Backend\Document::class]);
   Route::resource('actionLogs', 'FastController', ['model' => \Loopeer\QuickCms\Models\Backend\ActionLog::class]);
   Route::resource('exceptionLogs', 'FastController', ['model' => \Loopeer\QuickCms\Models\Backend\ExceptionLog::class]);

   Route::get('users/profile', 'UserController@getProfile');
   Route::post('users/profile', 'UserController@saveProfile');
   Route::resource('users', 'FastController', ['model' => \Loopeer\QuickCms\Models\Backend\User::class]);
   Route::post('users', 'UserController@storeUser');

   Route::get('roles/permissions/{id}', array('as' => 'admin.roles.permissions','uses' => 'RoleController@permissions'));
   Route::post('roles/permissions/{id}', array('as' => 'admin.roles.savePermissions','uses' => 'RoleController@savePermissions'));
   Route::resource('roles', 'FastController', ['model' => \Loopeer\QuickCms\Models\Backend\Role::class]);

   Route::get('selector/preview', 'SelectorController@preview');
   Route::get('selector/checkKey', 'SelectorController@checkKey');
   Route::resource('selector', 'SelectorController');

   Route::get('statistics/index', 'StatisticController@index');
   Route::get('statistics/chartDays', 'StatisticController@chartDays');
   Route::get('statistics/chartMonths', 'StatisticController@chartMonths');

   //pushes
   Route::get('pushes/batch', 'PushesController@batch');
   Route::post('pushes/save', 'PushesController@save');
   Route::resource('pushes', 'PushesController');

   Route::get('sendcloud/template', 'SendcloudController@template');
   Route::get('sendcloud/apiuser', 'SendcloudController@changeApiUser');
   Route::post('sendcloud/apiuser', 'SendcloudController@saveApiUser');
   Route::resource('sendcloud', 'SendcloudController');
   Route::get('sendcloud/{invokeName}/review', 'SendcloudController@review');
});

