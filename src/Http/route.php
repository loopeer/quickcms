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

Route::get('test', function() {
   echo 'hello world';
});

Route::get('admin/login', 'IndexController@getLogin');
Route::post('admin/login',array('middleware' => 'auth.login','as' => 'admin.login','uses' => 'IndexController@postLogin'));

Route::group(array('prefix' => 'admin','middleware' => 'auth.admin'), function () {
   if(env('APP_ENV') == 'local'){
      Route::resource('build', 'AutoBuildController', array('except'=>'show'));
      Route::get('getColumns', 'AutoBuildController@getColumns');
   }
   Route::get('/', 'IndexController@getIndex');
   Route::get('logout',array('as' => 'admin.logout','uses' => 'IndexController@logout'));
   Route::get('index', 'IndexController@index');
   Route::get('index/getLoginLog', 'IndexController@getLoginLog');

//   Route::resource('dashboard', 'DashboardController',array('except' => 'show'));

   // 图片上传
   Route::post('blueimp', array('as'=>'admin.blueimp.upload', 'uses'=>'BlueimpController@upload'));
   Route::get('blueimp/{id}', array('as'=>'admin.blueimp.delete', 'uses'=>'BlueimpController@destroy'));
   Route::get('blueimp', array('as'=>'admin.blueimp.index', 'uses'=>'BlueimpController@getImage'));

   Route::get('users/search', 'UserController@search');
   Route::resource('users', 'UserController', array('except'=>'show'));
   Route::get('users/edit/{id}', 'UserController@edit');
   Route::get('users/changeStatus/{id}', 'UserController@changeStatus');
   Route::get('users/role/{id}', 'UserController@getRole');
   Route::post('users/role', array('as'=>'admin.users.role','uses'=>'UserController@saveRole'));
   Route::get('users/checkEmail', 'UserController@checkEmail');

   Route::resource('roles', 'RoleController', array('except'=>'show'));
   Route::get('roles/search', 'RoleController@search');
   Route::get('roles/permissions/{id}', array('as' => 'admin.roles.permissions','uses' => 'RoleController@permissions'));
   Route::post('roles/permissions/{id}', array('as' => 'admin.roles.savePermissions','uses' => 'RoleController@savePermissions'));

//   Route::resource('logs', 'LogsController', array('except'=>'show'));
//   Route::get('logs/delete/{log_id}', array('as'=>'admin.logs.delete', 'uses'=>'LogsController@delete'));
//   Route::get('logs/emptyLogs', array('as'=>'admin.logs.emptyLogs', 'uses'=>'LogsController@emptyLogs'));

   Route::resource('permissions', 'PermissionController', array('except'=>'show'));
   Route::get('permissions/search', 'PermissionController@search');

   Route::resource('permissions', 'PermissionController');
   Route::get('permissions/delete/{id}',array('as'=>'admin.permissions.delete','uses'=>'PermissionController@delete'));
   Route::post('permissions/update/{id}',array('as'=>'admin.permissions.update','uses'=>'PermissionController@update'));

   //运维管理
   Route::resource('logs', 'LogController', array('except'=>'show'));
   Route::get('logs/search', 'LogController@search');
   Route::get('logs/emptyLogs', array('as'=>'admin.logs.emptyLogs', 'uses'=>'LogController@emptyLogs'));

   Route::resource('feedbacks', 'FeedbackController', array('except'=>'show'));
   Route::get('feedbacks/search', array('as'=>'admin.feedbacks.search', 'uses'=>'FeedbackController@search'));

   Route::resource('versions', 'VersionController', array('except'=>'show'));
   Route::get('versions/search', 'VersionController@search');

   Route::resource('systems', 'SystemController', array('except'=>'show'));
   Route::post('systems/uploadLogo', array('as' => 'admin.systems.uploadLogo', 'uses' => 'SystemController@uploadLogo'));
   Route::post('systems/title', array('as' => 'admin.systems.title', 'uses' => 'SystemController@title'));
});