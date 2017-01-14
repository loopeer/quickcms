<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: WangKaiBo
 * Date: 16/1/26
 * Time: 上午10:47
 */
namespace Loopeer\QuickCms\Http\Controllers;

use App\Models\Label;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use View;
use Session;
use Response;
use Input;
use DB;

class ExcelController extends BaseController
{

    public function export()
    {

        $labels = DB::table('labels')->get();
//        \Log::info($labels);
        return Excel::create('test')->sheet('sheet1', function($sheet) use ($labels) {
            $sheet->fromArray(json_decode(json_encode($labels)), null, 'A1', true);
        })->export('xlsx');
    }

}
