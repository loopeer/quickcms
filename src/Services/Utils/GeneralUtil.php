<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 16/4/19
 * Time: 下午5:37
 */
namespace Loopeer\QuickCms\Services\Utils;

class GeneralUtil {

    public static function curdAction($curl_action = array()) {
        $default_action = array('create' => true, 'edit' => true, 'detail' => true, 'delete' => true);
        if(count($curl_action) > 0) {
            foreach($curl_action as $action_key => $action_val) {
                if(isset($action_val)) {
                    $default_action[$action_key] = $action_val;
                }
            }
        }
        return $default_action;
    }
}