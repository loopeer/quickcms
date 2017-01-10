<?php

namespace Loopeer\QuickCms\Http\Controllers\Api;

use DB;
use Illuminate\Pagination\Paginator;
use Input;
use View;

class BaseController extends ApiBaseController {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	/**
	 * 设置分页对象的当前页
	 */
	protected function setCurrentPage() {
		$page = Input::get(self::getParameterKeyPage());
		$pageSize = Input::get(self::getParameterKeyPageSize());
		// validate parameters
		self::validatePaginationParameters($page, $pageSize);
		Paginator::currentPageResolver(function() use ($page) {
			return $page;
		});
		return $pageSize;
	}

	/**
	 * 增加查询经纬度
	 * @param $pagination
	 * @param $table
	 * @return mixed
	 */
	protected function addSelectDistance($pagination, $table) {
		$R=6370996.81;
		$longitude = Input::get('longitude',null);
		$latitude = Input::get('latitude',null);
		$prefix = config('database.connections.mysql.prefix');
		if(isset($prefix)) {
			$table = $prefix . $table;
		}
		if(isset($longitude) && isset($latitude)) {
			$pagination = $pagination->addSelect(DB::Raw('acos(cos('. $table . '.latitude*pi()/180)*cos('
				. $latitude . '*pi()/180)*cos('. $table . '.longitude*pi()/180-'
				. $longitude . '*pi()/180)+sin('. $table . '.latitude*pi()/180)*sin('
				. $latitude . '*pi()/180))*'
				. $R . 'as distance'));
			$pagination = $pagination->orderBy('distance', 'asc');
		}
		return $pagination;
	}
}
