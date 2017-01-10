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

class AdvertisementController extends BaseController {

    protected $model;

    public function __construct() {
        $reflectionClass = new \ReflectionClass(config('quickApi.model_bind.advert'));
        $this->model = $reflectionClass->newInstance();
    }

    /**
     * 广告列表
     * @param $request
     * @return mixed
     */
    public function index(Request $request) {
        $adverts = $this->model->where('index', $request->input('index', 0))->online()->sort()->get();
        return ApiResponse::responseSuccess($adverts);
    }

}