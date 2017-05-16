<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: dengyongbin
 * Date: 16/10/14
 * Time: 16:38
 */

namespace Loopeer\QuickCms\Http\Controllers\Api;

use Loopeer\QuickCms\Services\Validators\Validator;
use Response;
use Exception;

class ApiResponse extends Response {

    /**
     * API输入参数的校验
     * @param $validator
     * @param int $code
     * @param null $message
     * @return mixed
     * @throws Exception
     */
    public static function validation($validator, $code = 412, $message = NULL) {
        if ( !($validator instanceof Validator) ) {
            throw new Exception('Argument is not a Validator instance ('.get_class($validator).' found).');
        }
        $response = array('validation' => 'Passed!');
        if ($validator->fails()) {
            $errors = $validator->messages()->toArray();
            if (is_array($errors)) {
                $response = array();
                foreach ($errors as $key => $value) {
                    $response[$key] = $value[0];
                }
            } else {
                $response = $errors;
            }
        }
        return self::json([
            'code' => $code ? : config('quickApi.code.invalid_parameters'),
            'message' => $message ? : trans('messages.invalid_parameters'),
            'data' => $response
        ]);
    }

    /**
     * Response the success data
     * @param $data
     * @return mixed
     */
    public static function responseSuccess($data = NULL) {
        $ret = array(
            'code' => config('quickApi.code.success'),
            'message' => trans('messages.request_success'),
            'data' => $data
        );
        return Response::json($ret);
    }

    /**
     *
     * @param null $message
     * @param null $data
     * @return mixed
     */
    public static function responseSuccessWithMessage($message = NULL, $data = NULL) {
        $ret = array(
            'code' => config('quickApi.code.success'),
            'message' => $message ? : trans('messages.request_success'),
            'data' => $data
        );
        return Response::json($ret);
    }

    /**
     * Response success with paginate object
     * @param $pagination
     * @return mixed
     */
    public static function responseSuccessWithPagination($pagination) {
        $ret = array (
            'code' => config('quickApi.code.success'),
            'message' => trans('messages.request_success'),
            'page' => $pagination->currentPage(),
            'page_size' => $pagination->perPage(),
            'total_size' => $pagination->total(),
            'data' => $pagination->items()
        );
        return Response::json($ret);
    }

    public static function responseSuccessWithOffset($data, $pageSize, $totalSize) {
        $ret = array (
            'code' => config('quickApi.code.success'),
            'message' => trans('messages.request_success'),
            'page_size' => $pageSize,
            'total_size' => $totalSize,
            'data' => $data
        );
        return Response::json($ret);
    }

    public static function responseSuccessWithCustom($data, $custom) {
        $ret = array_merge(
            [
                'code' => config('quickApi.code.success'),
                'message' => trans('messages.request_success'),
                'data' => $data
            ],
            $custom
        );
        return Response::json($ret);
    }

    /**
     * Response failure
     * @param null $errorCode
     * @param null $errorMessage
     * @return mixed
     */
    public static function responseFailure($errorCode = NULL, $errorMessage = NULL) {
        $ret = array (
            'code' => $errorCode == NULL ? config('quickApi.code.failure') : $errorCode,
            'message' => $errorMessage == NULL ? trans('messages.default_error') : $errorMessage,
            'data' => NULL
        );
        return Response::json($ret);
    }

    /**
     * Response failure invalid parameters
     * @return mixed
     */
    public static function responseFailureInvalidParameters() {
        return self::responseFailure(config('quickApi.code.invalid_parameters'),
            trans('messages.invalid_parameters'));
    }

    /**
     * @param null $errorMessage
     * @return mixed
     */
    public static function errorNoContent($errorMessage = NULL) {
        return self::responseFailure(
            config('quickApi.code.no_content'),
            $errorMessage ? : trans('messages.content_not_exist')
        );
    }

    public static function errorForbidden($message = NULL, $data = NULL) {
        return self::json([
            'code' => 403,
            'message' => $message ? : 'error for bidden',
            'data' => $data
        ]);
    }

    /**
     * 用户权限未通过
     * @param null $data
     * @param int $code
     * @param string $message
     * @return mixed
     */
    public static function errorUnauthorized($data = NULL, $code = 401, $message = 'Not authenticated') {
        return self::json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function errorPreCondition($errorMessage = NULL) {
        return self::responseFailure(
            config('quickApi.code.precondition_failed'),
            $errorMessage ? : 'error for condition'
        );
    }

    /**
     * API版本不支持
     * @return mixed
     */
    public static function errorVersion() {
        return self::json([
            'code' => 505,
            'message' => 'API Version Not Supported',
            'data' => null
        ]);
    }
}