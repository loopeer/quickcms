<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 17/02/15
 * Time: 下午5:54
 */

namespace Loopeer\QuickCms\Http\Controllers\Backend;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Loopeer\QuickCms\Services\Utils\GeneralUtil;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App;
use Cache;
use Log;
use Redirect;

class FastController extends BaseController
{

    public function search(Model $model)
    {
        $redirect_value = isset($model->redirect_value) ? $model->redirect_value : null;
        return response()->json(self::fastQuery($model, 'paginate', $redirect_value));
    }

    public function index(Model $model)
    {
        $index = $model->index;
        $redirect_value = isset($model->redirect_value) ? $model->redirect_value : null;
        $queries = [];
        foreach ($index as $column) {
            if (isset($column['query'])) {
                $queries[] = $column;
            }
        }
        return view('backend::fasts.index', compact('model', 'queries', 'redirect_value'));
    }

    public function create(Model $model)
    {
        $data = new $model;
        return view('backend::fasts.create', compact('model', 'data'));
    }

    public function store(Model $model = null)
    {
        $data = Input::all();
        $message['result'] = true;
        try {
            if ($data['id']) {
                $builder = $model::find($data['id']);
                foreach($data as $k => $v) {
                    $builder->$k = $v;
                }
                $builder->save();
            } else {
                $model::create($data);
            }
        } catch (QueryException $ex) {
            $message['content'] = '数据库中已存在相同的数据，请修改你的数据。';
            Log::info($ex->getMessage());
            return back()->with('message', $message)->withInput($data);
        }
        $message['content'] = '数据保存成功。';
        return redirect()->to('admin/' . $model->route)->with('message', $message);
    }

    public function show(Model $model, $id)
    {
        $data = $model::find($id);
        return view('backend::fasts.detail', compact('model', 'data'));
    }

    public function edit(Model $model, $id)
    {
        $data = $model::find($id);
        return view('backend::fasts.create', compact('model', 'data'));
    }

    public function update(Model $model, $id)
    {
        $param = Input::all();
        foreach ($param as $key => $value) {
            switch($value) {
                case 'now':
                    $param[$key] = Carbon::now();
                    break;
                case 'admin':
                    $param[$key] = Auth::admin()->get()->email;
                    break;
                default:
                    break;
            }
        }
        return response()->json($model::find($id)->update($param));
    }

    public function destroy(Model $model, $id)
    {
        return response()->json($model::destroy($id));
    }

    /**
     * 全表导出
     * @param Model $model
     * @return mixed
     */
    public function dbExport(Model $model)
    {
        if ($model->buttons['dbExport']) {
            $table = $model->getTable();
            $data = DB::table($table)->get();
            return Excel::create($table)->sheet($table, function($sheet) use ($data) {
                $sheet->fromArray(collect($data)->map(function ($x) {
                    return (array)$x;
                })->toArray(), null, 'A1', true);
            })->export('xlsx');
        } else {
            App::abort('403');
        }
    }

    /**
     * 列表导出
     * @param Model $model
     * @return mixed
     */
    public function queryExport(Model $model)
    {
        if ($model->buttons['queryExport']) {
            $redirect_value = isset($model->redirect_value) ? $model->redirect_value : null;
            //查询数据
            $data = self::fastQuery($model, 'all', $redirect_value);
            $column_name = [];
            $table = $model->getTable();
            $index_columns = array_column($model->index, 'column');
            try {
                foreach ($data as &$row) {
                    foreach ($row as $key => &$value) {
                        foreach ($model->index as $k=> $index) {
                            //normal
                            if (isset($index['param']) && isset($index['type']) && $index['type'] == 'normal' && $k == $key) {
                                $value = strip_tags($index['param'][$value]);
                            }
                            //select
                            if (isset($index['param']) && isset($index['type']) && $index['type'] == 'select' && $k == $key) {
                                $selector_key = strip_tags($index['param']);
                                $selector_value = json_decode(GeneralUtil::getSelectorData($selector_key));
                                $value = $selector_value->$value;
                            }
                        }
                    }
                }

                //列名插入第一行
                foreach ($index_columns as $index_column) {
                    $column_name[] = trans('fasts.' . $model->route . '.' . $index_column);
                }
                array_unshift($data, $column_name);

                Excel::create($table)->sheet($table, function($sheet) use ($data) {
                    $sheet->rows($data);
                })->export('xlsx');

            } catch (Exception $e) {
                Log::info($e->getMessage());
                $message = ['result' => false, 'content' => '导出失败，请重试'];
                return Redirect::back()->with('message', $message);
            }

        } else {
            App::abort('403');
        }
    }
}