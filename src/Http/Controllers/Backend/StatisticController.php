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

use DB;
use Input;
use Loopeer\QuickCms\Models\Backend\Statistic;

class StatisticController extends BaseController {

    public function index() {
        $statistics = Statistic::select(DB::raw('statistic_key, sum(statistic_value) statistic_value'))
            ->groupBy('statistic_key')
            ->orderBy('sort')
            ->get();
        return view('backend::statistics.index', compact('statistics'));
    }

    public function chartDays() {
        $chart_date = Input::get('chart_date');
        return $this->queryStatistic($chart_date, 'day');
    }

    public function chartMonths() {
        $chart_year = Input::get('chart_year');
        return $this->queryStatistic($chart_year, 'month');
    }

    private function queryStatistic($statistic_time, $type = 'day') {
        $statistic_key = config('quickCms.statistic_key');
        $statistic_init = [];
        foreach($statistic_key as $key=>$value) {
            if($type == 'day') {
                $init_data = array_fill(1, 31, 0);
                $date_format = '%Y-%m';
            } else {
                $init_data = array_fill(1, 12, 0);
                $date_format = '%Y';
            }
            // 统计数据
            $statistics = Statistic::select(DB::raw($type . '(statistic_time) statistic_time, sum(statistic_value) statistic_value'))
                ->where('statistic_key', $value)
                ->whereRaw("date_format(statistic_time, '" . $date_format . "')='" . $statistic_time . "'")
                ->groupBy(DB::raw($type . '(statistic_time)'))
                ->get();
            foreach($statistics as $statistic) {
                $init_data[$statistic->statistic_time] = (int)$statistic->statistic_value;
            }
            $statistic_init[$key]['name'] = $value;
            $statistic_init[$key]['data'] = $init_data;
        }
        return json_encode($statistic_init);
    }
}