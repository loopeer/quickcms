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

namespace Loopeer\QuickCms\Http\Controllers;

use Illuminate\Http\Request;
use Loopeer\QuickCms\Models\Version;
use Session;
use Response;

class GeneralController extends BaseController
{

    public function search()
    {
        $ret = self::simplePage(config('general.versions_index_column'), new Version());
        return Response::json($ret);
    }

    public function index(Request $request)
    {
        $message = Session::get('message');
        $model = str_replace('admin/', '', $request->path());
        $column_name = config('general.' . $model . '_index_column_name');
        \Log::info($model);
        return view('backend::generals.index', compact('message', 'column_name'));
    }
}