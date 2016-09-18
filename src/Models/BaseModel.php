<?php

namespace Loopeer\QuickCms\Models;

use Carbon\Carbon;
use Loopeer\QuickCms\Services\Utils\QiniuUtil;

class BaseModel extends HookModel
{
    public function getCreatedAtAttribute($value) {
        $time = strtotime($value);
        if (is_int($time)) {
            return $time >= 0 ? date('Y-m-d H:i:s',$time) : 0;
        }
        return 0;
    }
    public function getUpdatedAtAttribute($value) {
        $time = strtotime($value);
        if (is_int($time)) {
            return $time >= 0 ? date('Y-m-d H:i:s',$time) : 0;
        }
        return 0;
    }

    public function getImageAttribute($value){
        $images = null;
        if(isset($va) && $va != '') {
            $qn_images = array();
            $images = explode(',', $value);
            if(count($images) > 1) {
                foreach($images as $image) {
                    $image = QiniuUtil::buildQiqiuImageUrl($image);
                    array_push($qn_images,$image);
                }
                return $qn_images;
            } else {
                return QiniuUtil::buildQiqiuImageUrl($value);
            }
        }
        return $images;
    }
}
