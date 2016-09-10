<?php

namespace Loopeer\QuickCms\Models\Api;

use Loopeer\QuickCms\Models\HookModel;
use Loopeer\QuickCms\Services\Utils\QiniuUtil;

class BaseModel extends HookModel
{
    protected $casts = [
        'id' => 'integer',
    ];

    public function getPublishTimeAttribute($value) {
        $time = strtotime($value);
        if (is_int($time)) {
            return $time >= 0 ? $time : 0;
        }
        return 0;
    }

    public function getImageAttribute($value) {
        if(is_null($value) || $value == ''){
            return null;
        }
        $images = explode(',', $value);
        foreach($images as $key => $image) {
            if(stripos($image, 'http:') === FALSE) {
                $images[$key] = QiniuUtil::buildQiqiuImageUrl($image);
            }
        }
        return implode(',', $images);
    }

    public function getAvatarAttribute($value) {
        if(is_null($value) || $value == ''){
            return null;
        }
        $images = explode(',', $value);
        foreach($images as $key => $image) {
            if(stripos($image, 'http:') === FALSE) {
                $images[$key] = QiniuUtil::buildQiqiuImageUrl($image);
            }
        }
        return implode(',', $images);
    }

}
