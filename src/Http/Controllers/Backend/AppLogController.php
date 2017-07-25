<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 16/1/18
 * Time: 下午1:43
 */
namespace Loopeer\QuickCms\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Loopeer\QuickCms\Http\Controllers\Api\ApiResponse;
use Loopeer\QuickCms\Models\Backend\AppLog;

class AppLogController extends BaseController {

    public function index()
    {
        $years = ['2017', '2018'];
        return view('backend::appLogs.dash', compact('years'));
    }

    public function getMonthChartsData(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $result = [
            'total_data' => $this->transformMonthData($this->queryMonthData($year, AppLog::query())),
            'android_data' => $this->transformMonthData($this->queryMonthData($year, AppLog::query()->where('platform', 'android'))),
            'ios_data' => $this->transformMonthData($this->queryMonthData($year, AppLog::query()->where('platform', 'ios')))
        ];
        return ApiResponse::responseSuccess($result);
    }

    public function getDayChartsData(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);
        $result = [
            'total_data' => $this->transformDayData($this->queryDayData($year, $month, AppLog::query())),
            'android_data' => $this->transformDayData($this->queryDayData($year, $month, AppLog::query()->where('platform', 'android'))),
            'ios_data' => $this->transformDayData($this->queryDayData($year, $month, AppLog::query()->where('platform', 'ios')))
        ];
        return ApiResponse::responseSuccess($result);
    }

    private function queryMonthData($year, $query)
    {
        return $query->whereYear('created_at', '=', $year)
            ->select(DB::raw('YEAR(created_at) year, MONTH(created_at) month, count(id) count'))
            ->groupBy('year')
            ->groupBy('month')
            ->get();
    }

    private function queryDayData($year, $month, $query)
    {
        return $query->whereYear('created_at', '=', $year)
            ->whereMonth('created_at', '=', $month)
            ->select(DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day, count(id) count'))
            ->groupBy('year')
            ->groupBy('month')
            ->groupBy('day')
            ->get();
    }

    private function transformMonthData($collection)
    {
        $months = range(1, 12);
        $data = collect($months)->map(function ($month) use ($collection) {
            if (in_array($month, $collection->lists('month')->toArray())) {
                return (int)$collection->filter(function ($item) use ($month) {
                    return $item->month == $month;
                })->first()->count;
            }
            return 0;
        });
        return $data;
    }

    private function transformDayData($collection)
    {
        $days = range(1, 31);
        $data = collect($days)->map(function ($day) use ($collection) {
            if (in_array($day, $collection->lists('day')->toArray())) {
                return (int)$collection->filter(function ($item) use ($day) {
                    return $item->day == $day;
                })->first()->count;
            }
            return 0;
        });
        return $data;
    }
}