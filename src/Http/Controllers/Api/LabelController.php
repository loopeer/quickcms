<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 16/8/17
 * Time: 上午9:48
 */
namespace Loopeer\QuickCms\Http\Controllers\Api;

use Illuminate\Http\Request;

class LabelController extends BaseController {

    protected $model;

    public function __construct() {
        $reflectionClass = new \ReflectionClass(config('quickApi.model_bind.label'));
        $this->model = $reflectionClass->newInstance();
    }

    /**
     * 标签列表
     * @return mixed
     */
    public function index() {
        $labels = $this->model->online()->sort()->get();
        return ApiResponse::responseSuccess($labels);
    }

}