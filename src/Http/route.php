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

Route::group(array('prefix' => 'admin','middleware' => 'auth.admin'), function () {
   if(env('APP_ENV') == 'local'){
      Route::resource('build', 'AutoBuildController', array('except'=>'show'));
      Route::get('getColumns', 'AutoBuildController@getColumns');
   }

   Route::get('/', 'IndexController@getIndex');
   Route::get('logout',array('as' => 'admin.logout','uses' => 'IndexController@logout'));
   Route::get('index', 'IndexController@index');


   Route::resource('generals', 'GeneralController@index');
   Route::get('generals/search', 'GeneralController@search');

   // 图片上传
   Route::post('blueimp', array('as'=>'admin.blueimp.upload', 'uses'=>'BlueimpController@upload'));
   Route::get('blueimp/{id}', array('as'=>'admin.blueimp.delete', 'uses'=>'BlueimpController@destroy'));
   Route::get('blueimp', array('as'=>'admin.blueimp.index', 'uses'=>'BlueimpController@getImage'));

   //文件上传
   Route::post('dropzone/upload', 'DropzoneController@upload');
   Route::get('dropzone/fileList/{id}', 'DropzoneController@fileList');

   Route::resource('users', 'UserController', array('except'=>'show'));
   Route::get('users/search', 'GeneralController@search');
   Route::get('users', 'GeneralController@index', array('except'=>'show'));
   Route::get('users/edit/{id}', 'UserController@edit');
   Route::get('users/changeStatus/{id}', 'UserController@changeStatus');
   Route::get('users/role/{id}', 'UserController@getRole');
   Route::post('users/role', array('as'=>'admin.users.role','uses'=>'UserController@saveRole'));
   Route::get('users/checkEmail', 'UserController@checkEmail');

   Route::resource('roles', 'GeneralController', array('except'=>'show'));
   Route::get('roles/search', 'GeneralController@search');
   Route::get('roles/permissions/{id}', array('as' => 'admin.roles.permissions','uses' => 'RoleController@permissions'));
   Route::post('roles/permissions/{id}', array('as' => 'admin.roles.savePermissions','uses' => 'RoleController@savePermissions'));

   Route::resource('permissions', 'PermissionController', array('except'=>'show'));
   Route::get('permissions/search', 'PermissionController@search');

   //Route::resource('permissions', 'PermissionController');
   Route::get('permissions/delete/{id}',array('as'=>'admin.permissions.delete','uses'=>'PermissionController@delete'));
   Route::post('permissions/update/{id}',array('as'=>'admin.permissions.update','uses'=>'PermissionController@update'));

   Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

   //运维管理
   Route::resource('actionLogs', 'LogController', array('except'=>'show'));
   Route::get('actionLogs/search', 'LogController@search');
   Route::get('actionLogs/emptyLogs', array('as'=>'admin.logs.emptyLogs', 'uses'=>'LogController@emptyLogs'));

   Route::resource('feedbacks', 'GeneralController', array('except'=>'show'));
   Route::get('feedbacks/search', 'GeneralController@search');

   Route::resource('versions', 'GeneralController', array('except'=>'show'));
   Route::get('versions/search', 'GeneralController@search');
   Route::get('versions/changeStatus/{id}', 'GeneralController@changeStatus');

   Route::resource('selector', 'SelectorController', array('except'=>'show'));
   Route::get('selector/search', 'SelectorController@search');
   Route::get('selector/preview', 'SelectorController@preview');
   Route::get('selector/checkKey', 'SelectorController@checkKey');

   Route::get('statistics/index', 'StatisticController@index');
   Route::get('statistics/chartDays', 'StatisticController@chartDays');
   Route::get('statistics/chartMonths', 'StatisticController@chartMonths');

   Route::resource('document', 'DocumentController', array('except'=>'show'));
   Route::get('document/search', 'DocumentController@search');

   //pushes
   Route::resource('pushes', 'PushesController', array('except'=>'show'));
   Route::get('pushes/search', 'PushesController@search');
   Route::get('pushes/batch', 'PushesController@batch');
   Route::post('pushes/save', 'PushesController@save');

   Route::resource('systems', 'GeneralController', array('except'=>'show'));
   Route::get('systems/search', 'GeneralController@search');
});