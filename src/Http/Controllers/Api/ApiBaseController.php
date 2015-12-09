<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: YongBin Deng
 * Date: 2/2/15
 * Time: 12:50
 */

namespace Loopeer\QuickCms\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Input;
use Response;

class ApiBaseController extends Controller {

    /**
     * Validate the parameters
     * @param $params
     * @return bool
     */
    public function isParametersExist($params) {
        if (is_null($params)) {
            return true;
        }
        $validate = true;
        foreach($params as $key) {
            if (!Input::has($key)) {
                $validate = false;
                break;
            }
        }
        return $validate;
    }

    /**
     * Validate the parameters, exist any parameters is ok
     * @param $params
     * @return bool
     */
    public function isAnyParametersExist($params) {
        if (is_null($params)) {
            return true;
        }
        $validate = false;
        foreach($params as $key) {
            if (!Input::has($key)) {
                $validate = true;
                break;
            }
        }
        return $validate;
    }

    /**
     * Validate the pagination parameters
     * @return bool
     */
    public function isPaginationParametersExist() {
        $params = array(self::getParameterKeyPage(), self::getParameterKeyPageSize());
        return self::isParametersExist($params);
    }

    /**
     * Validate the pagination parameters: page & pageSize
     * @param $page
     * @param $pageSize
     */
    public function validatePaginationParameters(&$page, &$pageSize) {
        $page = intval($page);
        $pageSize = intval($pageSize);
        if ($page < 1) {
            $page = config('quickcms.paginator_default_page');
        }
        if ($pageSize <= 0) {
            $pageSize = config('quickcms.paginator_default_page_size');
        }
    }

    /**
     * Response User forbidden
     *
     * @param $user
     * @return mixed
     */
    public function responseUserForbidden($user) {
        $ret = array();
        if ($user->status == config('quickcms.user_status_forbidden')) {
            $ret['code'] = config('quickcms.code_user_is_forbidden');
            $ret['message'] = config('quickcms.message_user_is_forbidden');
        } else if ($user->status == config('quickcms.user_status_disabled')) {
            $ret['code'] = config('quickcms.code_user_is_disabled');
            $ret['message'] = config('quickcms.message_user_is_disabled');
        }
        $ret['data'] = NULL;
        return Response::json($ret);
    }

    /**
     * Get the page parameter key
     * @return mixed
     */
    public function getParameterKeyPage() {
        return config('quickcms.REQUEST_PARAMETER_PAGE');
    }


    /**
     * Get the pagesize parameter key
     * @return mixed
     */
    public function getParameterKeyPageSize() {
        return config('quickcms.REQUEST_PARAMETER_PAGE_SIZE');
    }

    /**
     * Get the UserId parameter key
     * @return mixed
     */
    public function getParameterKeyUserId() {
        return config('quickcms.REQUEST_PARAMETER_USER_ID');
    }

    /**
     * Get the token parameter key
     * @return mixed
     */
    public function getParameterKeyToken() {
        return config('quickcms.REQUEST_PARAMETER_TOKEN');
    }

    /**
     * Check if the user is forbidden
     * @param $user
     * @return bool
     */
    public function isUserForbidden($user) {
        if (is_null($user)) {
            return false;
        }
        if ($user->status != config('quickcms.user_status_usable')) {
            return true;
        }
        return false;
    }

    /**
     * 产生token
     * @param int $size
     * @return string
     */
    public static function generateToken($size = 32) {
        do {
            $key = openssl_random_pseudo_bytes($size , $strongEnough);
        } while(!$strongEnough);
        $key = str_replace('+', '', base64_encode($key));
        $key = str_replace('/', '', $key);
        return base64_encode($key);
    }

} 