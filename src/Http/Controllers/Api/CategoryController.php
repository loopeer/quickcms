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

class CategoryController extends BaseController {

    protected $model;

    public function __construct() {
        $reflectionClass = new \ReflectionClass(config('quickApi.model_bind.category'));
        $this->model = $reflectionClass->newInstance();
    }

    /**
     * 分类列表
     * @return mixed
     */
    public function index() {
        $categories = $this->model->online()->sort()->get();
        return ApiResponse::responseSuccess($categories);
    }

}