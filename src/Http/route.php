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
   if(config('quickcms.sql_log_switch')) {
      $logger = \Loopeer\QuickCms\Services\Utils\LogUtil::getLogger('sql', 'sql');
      $logger->addInfo($query . '   params = ' . implode(',', $params) . '  time = ' . $time . '   conn = ' . $conn);
   }
});

Route::get('admin/login', 'IndexController@getLogin');
Route::post('admin/login',array('middleware' => 'auth.login','as' => 'admin.login','uses' => 'IndexController@postLogin'));

Route::get('test/push', 'TestController@push');

Route::get('admin/index/getLoginLog', 'IndexController@getLoginLog');
Route::get('admin/users/search', 'GeneralController@search');
Route::get('admin/roles/search', 'GeneralController@search');
Route::get('admin/permissions/search', 'PermissionController@search');
Route::get('admin/actionLogs/search', 'LogController@search');
Route::get('admin/versions/search', 'GeneralController@search');
Route::get('admin/feedbacks/search', 'GeneralController@search');
Route::get('admin/selector/search', 'SelectorController@search');
Route::get('admin/document/search', 'DocumentController@search');
Route::get('admin/pushes/search', 'PushesController@search');
Route::get('admin/systems/search', 'GeneralController@search');
Route::get('admin/generals/search', 'GeneralController@search');

Route::group(array('prefix' => 'admin','middleware' => 'auth.admin'), function () {
   if(env('APP_ENV') == 'local'){
      Route::resource('build', 'AutoBuildController');
      Route::get('getColumns', 'AutoBuildController@getColumns');
   }

   Route::get('/', 'IndexController@getIndex');
   Route::get('logout',array('as' => 'admin.logout','uses' => 'IndexController@logout'));
   Route::get('index', 'IndexController@index');

   Route::resource('generals', 'GeneralController@index');

   // 图片上传
   Route::post('blueimp', array('as'=>'admin.blueimp.upload', 'uses'=>'BlueimpController@upload'));
   Route::get('blueimp/{id}', array('as'=>'admin.blueimp.delete', 'uses'=>'BlueimpController@destroy'));
   Route::get('blueimp', array('as'=>'admin.blueimp.index', 'uses'=>'BlueimpController@getImage'));

   //文件上传
   Route::post('dropzone/upload', 'DropzoneController@upload');
   Route::get('dropzone/fileList/{id}', 'DropzoneController@fileList');

   Route::get('users', 'GeneralController@index');
   Route::get('users/edit/{id}', 'UserController@edit');
   Route::get('users/changeStatus/{id}', 'UserController@changeStatus');
   Route::get('users/role/{id}', 'UserController@getRole');
   Route::post('users/role', array('as'=>'admin.users.role','uses'=>'UserController@saveRole'));
   Route::get('users/checkEmail', 'UserController@checkEmail');
   Route::get('users/update', 'UserController@update');
   Route::post('users/update', 'UserController@profile');
   Route::resource('users', 'UserController');

   Route::get('roles/permissions/{id}', array('as' => 'admin.roles.permissions','uses' => 'RoleController@permissions'));
   Route::post('roles/permissions/{id}', array('as' => 'admin.roles.savePermissions','uses' => 'RoleController@savePermissions'));
   Route::resource('roles', 'GeneralController');

   Route::get('permissions/delete/{id}',array('as'=>'admin.permissions.delete','uses'=>'PermissionController@delete'));
   Route::post('permissions/update/{id}',array('as'=>'admin.permissions.update','uses'=>'PermissionController@update'));
   Route::resource('permissions', 'PermissionController');

   Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

   //运维管理
   Route::get('actionLogs/emptyLogs', array('as'=>'admin.logs.emptyLogs', 'uses'=>'LogController@emptyLogs'));
   Route::resource('actionLogs', 'LogController');

   Route::resource('feedbacks', 'GeneralController');

   Route::get('versions/changeStatus/{id}', 'GeneralController@changeStatus');
   Route::resource('versions', 'GeneralController');

   Route::get('selector/preview', 'SelectorController@preview');
   Route::get('selector/checkKey', 'SelectorController@checkKey');
   Route::resource('selector', 'SelectorController');

   Route::get('statistics/index', 'StatisticController@index');
   Route::get('statistics/chartDays', 'StatisticController@chartDays');
   Route::get('statistics/chartMonths', 'StatisticController@chartMonths');

   Route::resource('document', 'DocumentController');

   //pushes
   Route::get('pushes/batch', 'PushesController@batch');
   Route::post('pushes/save', 'PushesController@save');
   Route::resource('pushes', 'PushesController');

   Route::resource('systems', 'GeneralController');

   Route::get('sendcloud/template', 'SendcloudController@template');
   Route::get('sendcloud/normal', 'SendcloudController@normal');
   Route::resource('sendcloud', 'SendcloudController');
   Route::get('sendcloud/{invokeName}/review', 'SendcloudController@review');
});

