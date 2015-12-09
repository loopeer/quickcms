<?php

namespace Loopeer\QuickCms\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Loopeer\QuickCms\Services\Utils\QiniuUtil;

class BaseModel extends Model
{
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
    protected $guarded = array('created_at', 'updated_at', 'deleted_at');

    public function getCreatedAtAttribute() {
        $time = strtotime($this->attributes['created_at']);
        if (is_int($time)) {
            return $time >= 0 ? date('Y-m-d H:i:s',$time) : 0;
        }
        return 0;
    }
    public function getUpdatedAtAttribute() {
        $time = strtotime($this->attributes['updated_at']);
        if (is_int($time)) {
            return $time >= 0 ? date('Y-m-d H:i:s',$time) : 0;
        }
        return 0;
    }

    public function getImageAttribute(){
        $images = null;
        if(isset($this->attributes['image']) && $this->attributes['image'] != ''){
            $qn_images=array();
            $images = explode(',',$this->attributes['image']);
            foreach($images as $image){
                $image = QiniuUtil::buildQiqiuImageUrl($image);
                array_push($qn_images,$image);
            }
            return $qn_images;
        }
        return $images;
    }
}
