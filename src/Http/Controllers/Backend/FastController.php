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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App;

class FastController extends BaseController
{

    public function search(Model $model)
    {
        return response()->json(self::fastQuery($model));
    }

    public function index(Model $model)
    {
        $index = $model->index;
        $queries = [];
        foreach ($index as $column) {
            if (isset($column['query'])) {
                $queries[] = $column;
            }
        }
        return view('backend::fasts.index', compact('model', 'queries'));
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
            \Log::info($ex->getMessage());
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

    public function dbExport(Model $model)
    {
        if ($model->buttons['dbExport']) {
            $table = with($model)->getTable();
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

    public function queryExport(Model $model)
    {
        if ($model->buttons['queryExport']) {

        } else {

        }
    }
}