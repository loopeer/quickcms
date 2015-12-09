<?php

namespace Loopeer\QuickCms\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Loopeer\QuickCms\Services\Utils\QiniuUtil;

class BaseModel extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
    protected $guarded = array('created_at', 'updated_at', 'deleted_at');

    protected $casts = [
        'id' => 'integer',
    ];

    public function getPublishTimeAttribute() {
        $time = strtotime($this->attributes['publish_time']);
        if (is_int($time)) {
            return $time >= 0 ? $time : 0;
        }
        return 0;
    }

    public function getImageAttribute() {
        if(is_null($this->attributes['image']) || $this->attributes['image'] == ''){
            return null;
        }
        $images = explode(',', $this->attributes['image']);
        foreach($images as $key => $image) {
            if(stripos($image, 'http:') === FALSE) {
                $images[$key] = QiniuUtil::buildQiqiuImageUrl($image);
            }
        }
        return implode(',', $images);
    }

    public function getAvatarAttribute() {
        if(is_null($this->attributes['avatar']) || $this->attributes['avatar'] == ''){
            return null;
        }
        $images = explode(',', $this->attributes['avatar']);
        foreach($images as $key => $image) {
            if(stripos($image, 'http:') === FALSE) {
                $images[$key] = QiniuUtil::buildQiqiuImageUrl($image);
            }
        }
        return implode(',', $images);
    }

    public function getSexAttribute(){
        $sex = $this->attributes['sex'];
        if(is_null($sex)){
            return '';
        }
        return $sex;
    }

}
