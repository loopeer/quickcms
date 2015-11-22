<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: msy
 * Date: 15-11-19
 * Time: 下午3:45
 */
namespace Loopeer\QuickCms\Http\Controllers;

use View;
use Input;
use Log;
use Session;
use DB;
use Redirect;
use Response;
use Auth;
use File;
use Exception;

class AutoBuildController extends BaseController {

    protected $default_controller_namespace;
    protected $default_controller_path;
    protected $default_model_namespace;
    protected $default_model_names;
    protected $default_view_path;
    protected $default_index_blade;
    protected $controller_stub;
    protected $model_stub;

    public function __construct(){
        $this->default_controller_path = app_path('Http/Controllers/');
        $this->default_controller_namespace = 'App\Http\Controllers';
        $this->default_model_path = app_path('Models/');
        $this->default_model_namespace = 'App\Model';
        $this->default_view_path = base_path('resources/views/');
        $this->default_index_blade = 'index';
        $this->controller_stub = __DIR__ . '/../../../stubs/controller.stub';
        $this->model_stub = __DIR__ . '/../../../stubs/model.stub';
    }

    public function index(){
        $message = Session::get('message');
        $action = route('admin.build.store');
        $tables = DB::select('show tables');
        foreach($tables as $key => $table){
            $tables[$key] = (array)$table;
        }
        $first_table = $tables[0]['Tables_in_'.config('database.connections.mysql.database')];
        $default_columns = DB::select('show columns from ' . $first_table);
        foreach($default_columns as $default_column){
            $default_column_fields[] = $default_column->Field;
        }
        $default_column_fields = implode(',', $default_column_fields);
        return view('backend::build.index', compact('tables', 'action', 'default_column_fields', 'message'));
    }

    public function getColumns(){
        $table = Input::get('table');
        $columns = DB::select('show columns from ' . $table);
        foreach($columns as $column){
            $column_fields[] = $column->Field;
        }
        $column_fields = implode(',', $column_fields);
        return $column_fields;
    }

    public function store(){
        $table = Input::get('table');
        $columns = Input::get('columns');
        $controller_name = Input::get('controller_name');
        $controller_path = Input::get('controller_path');
        $controller_namespace = Input::get('controller_namespace');
        $model_name = Input::get('model_name');
        $model_path = Input::get('model_path');
        $model_namespace = Input::get('model_namespace');
        $view_path = Input::get('view_path');
        $index_blade = Input::get('index_blade');

        $columns = explode(',', $columns);
        $model_name = ucwords(strtolower($model_name));
        $table = str_replace(config('database.connections.mysql.prefix'), '', $table);

        $real_controller_namespace = empty($controller_namespace) ? $this->default_controller_namespace : $controller_namespace;
        $real_controller_path = empty($controller_path) ? $this->default_controller_path : $this->default_controller_path . $controller_path . '/';
        $real_model_namespace = empty($model_namespace) ? $this->default_model_namespace : $model_namespace;
        $real_model_path = empty($model_path) ? $this->default_model_path : $this->default_model_path . $model_path . '/';
        $real_view_path = empty($view_path) ? $this->default_view_path : $this->default_view_path . $view_path . '/';
        $real_index_blade = empty($index_blade) ? $this->default_index_blade : $index_blade;

        try{
            $controller_ret = $this->buildNewController($columns, $controller_name, $real_controller_path, $real_controller_namespace, $model_name, $real_model_namespace, $real_view_path, $real_index_blade);
            $model_ret = $this->buildNewModel($table, $columns, $model_name, $real_model_namespace, $real_model_path);
            if(!$controller_ret['result']){
                throw new Exception($controller_ret['message']);
            }
            if(!$model_ret['result']){
                throw new Exception($model_ret['message']);
            }
            $message = array('result' => true, 'content' => $controller_ret['message'] . '，' . $model_ret['message']);
            return redirect('admin/build')->with('message', $message);
        }catch (Exception $e){
            $message = array('result' => false, 'content' => $e->getMessage());
            return redirect('admin/build')->with('message', $message);
        }
    }

    private function buildNewController($columns, $controller_name, $controller_path, $controller_namespace, $model_name, $model_namespace, $view_path, $index_blade){
        try{
            $columns = implode("','", $columns);
            $controller_file = $controller_path . $controller_name . '.php';
            if(File::exists($controller_file)){
                $ret = array('result' => false, 'message' => '创建失败，' .$controller_file. '文件已存在');
                return $ret;
            }

            if($view_path == $this->default_view_path){
                $index_view_path = $index_blade;
            }else{
                $index_view_path = str_replace('/', '.', str_replace($this->default_view_path, '', $view_path . $index_blade));
            }

            File::copy($this->controller_stub, $controller_file);
            File::put($controller_file, str_replace('{{ControllerNamespace}}', $controller_namespace, File::get($controller_file)));
            File::put($controller_file, str_replace('{{ControllerName}}', $controller_name, File::get($controller_file)));
            File::put($controller_file, str_replace('{{ModelNameSpace}}', $model_namespace, File::get($controller_file)));
            File::put($controller_file, str_replace('{{Model}}', $model_name, File::get($controller_file)));
            File::put($controller_file, str_replace('{{Columns}}', $columns, File::get($controller_file)));
            File::put($controller_file, str_replace('{{IndexViewPath}}', $index_view_path, File::get($controller_file)));

            $ret = array('result' => true, 'message' => '成功创建' . $controller_file);
            return $ret;
        }catch (Exception $e){
            $ret = array('result' => false, 'message' => $e->getMessage());
            return $ret;
        }
    }

    private function buildNewModel($table, $columns, $model_name, $model_namespace, $model_path){
        try{
            $fillable = implode("','", $columns);
            $model_file = $model_path . $model_name . '.php';
            if(!File::exists($model_path)){
                mkdir($model_path, 0777, true);
            }
            if(File::exists($model_file)){
                $ret = array('result' => false, 'message' => '创建失败，' .$model_file. '文件已存在');
                return $ret;
            }
            File::copy($this->model_stub, $model_file);
            File::put($model_file, str_replace('{{ModelName}}', $model_name, File::get($model_file)));
            File::put($model_file, str_replace('{{ModelNamespace}}', $model_namespace, File::get($model_file)));
            File::put($model_file, str_replace('{{table}}', $table, File::get($model_file)));
            File::put($model_file, str_replace('{{fillable}}', $fillable, File::get($model_file)));

            $ret = array('result' => true, 'message' => '成功创建' . $model_file);
            return $ret;

        }catch (Exception $e){
            $ret = array('result' => false, 'message' => $e->getMessage());
            return $ret;
        }
    }

    //TODO
    public function buildNewViews(){

    }
}