<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/12/15
 * Time: 下午5:54
 */

namespace Loopeer\QuickCms\Http\Controllers\General;

use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Loopeer\QuickCms\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{

    protected $routeName;
    protected $model;

    public function __construct(Request $request) {
        try {
            $routePath = preg_replace('/(admin\/)|(\/create)|(\/search)|(\/edit)|(\/changeStatus)|(\/detail)|\/{(?!custom_id).*?}/', '',
                Route::getCurrentRoute()->getPath());
            $routePath = str_replace('{custom_id}', Route::getCurrentRoute()->parameter('custom_id'), $routePath);

            if (is_null(Route::getCurrentRoute()->getName())) {
                $this->routeName = preg_replace('/(\/\d*\/)|(\/)/', '.', $routePath);
            } else {
                $this->routeName = preg_replace('/(admin.)|(.\w*$)/', '', Route::getCurrentRoute()->getName());
            }
            \Log::info(config('quickCms.general_model_class.' . $this->routeName));
            $reflectionClass = new \ReflectionClass(config('quickCms.general_model_class.' . $this->routeName));
            $this->model = $reflectionClass->newInstance();
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
        parent::__construct();
    }

    public function search(Request $request)
    {
        $length = $request->input('length');
        self::setCurrentPage();
        $paginate = $this->model->paginate($length);
        $ret = self::queryPage($this->model->getIndexColumns(), $paginate);
        return response()->json($ret);
    }

    public function index()
    {
        \Log::info($this->model->getButtons());
        $data = [
            'buttons' => $this->model->getButtons(),
            'operateStyle' => $this->model->getOperateStyle(),
            'actions' => $this->model->getActions(),
            'indexColumnNames' => $this->model->getIndexColumnNames(),
            'indexColumns' => $this->model->getIndexColumns(),
            'orderAbles' => $this->model->getOrderAbles(),
            'orderSorts' => $this->model->getOrderSorts(),
            'widths' => $this->model->getWidths(),
            'routeName' => $this->routeName
        ];
        return view('backend::general.index', $data);
    }

    public function store()
    {

    }

    public function create()
    {

    }

    public function edit($id)
    {

    }

    public function destroy($id)
    {

    }

    public function show($id)
    {

    }
}