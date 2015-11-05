<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/9/22
 * Time: 下午1:41
 */
namespace Loopeer\QuickCms\Http\Controllers;

use App\Http\Controllers\Controller;
use Loopeer\QuickCms\Models\Permission;
use Loopeer\QuickCms\Models\System;
use Carbon\Carbon;
use Input;
use Illuminate\Pagination\Paginator;
use Log;
use Cache;
use Auth;
use Loopeer\QuickCms\Models\User;
use Request;

/**
 * 后台Controller基类
 * Class BaseController
 * @package App\Http\Controllers\Backend
 */
class BaseController extends Controller
{

    private $menu;

    public function __construct()
    {
//        $route_url = $this->getRouter()->cu;
//        $permission = Permission::select('name','display_name')->where('name', $route_url)->first();
//        if(!empty($permission)) {
//            Log::info($permission->display_name);
//        }
//        if (!Cache::has('websiteTitle')) {
//             Cache::rememberForever('websiteTitle', function() {
//                return System::find(1)['title'];
//            });
//        }
    }

    /**
     * 分页查询封装函数
     * @param array $show_column ['id','name','email']
     * @param object $model new Model
     * @return array
     */
    public function simplePage($show_column, $model) {
        $search = Input::get('search')['value'];
        $order = Input::get('order')['0'];
        $length = Input::get('length');
        self::setCurrentPage($length);
        $order_sql = $show_column[$order['column']] . ' ' . $order['dir'];
        $str_column = implode(',', $show_column);
        $paginate = $model->selectRaw($str_column)
            ->whereRaw("concat_ws(" . $str_column . ") like '%" . $search . "%'")
            ->orderByRaw($order_sql)
            ->paginate($length);
        Log::info($paginate);
        $ret = self::getPageDate($show_column, $paginate);
        return $ret;
    }

    /**
     * 自定义查询分页函数
     * @param array $show_column ['id','name','email']
     * @param array $paginate query sql
     * @return array
     */
    public function queryPage($show_column, $paginate) {
        $ret = self::getPageDate($show_column, $paginate);
        return $ret;
    }

    /**
     * 设置分页对象的当前页
     */
    public function setCurrentPage() {
        $start = Input::get('start');
        $length = Input::get('length');
        $page = ($start / $length) + 1;
        Paginator::currentPageResolver(function() use ($page) {
            return $page;
        });
    }

    /**
     * 获取分页对象数据
     * @param array $show_column ['id','name','email']
     * @param array $paginate query sql
     * @return array
     */
    private function getPageDate($show_column, $paginate) {
        $draw = Input::get('draw');
        $data = array();
        foreach($paginate->items() as $item) {
            $obj = array();
            foreach($show_column as $column) {
                if($item->$column instanceof Carbon) {
                    array_push($obj, $item->$column->format('Y-m-d H:i:s'));
                } else {
                    array_push($obj, $item->$column);
                }
            }
            array_push($data, $obj);
        }
        $ret = array(
            'draw' => $draw,
            'recordsTotal' => $paginate->total(),
            'recordsFiltered' => $paginate->total(),
            'data' => $data,
        );
        return $ret;
    }
}