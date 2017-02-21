<?php

namespace Loopeer\QuickCms\Models;

use Carbon\Carbon;
use Loopeer\QuickCms\Services\Utils\QiniuUtil;

class BaseModel extends HookModel
{
    protected function castAttribute($key, $value)
    {
        if (is_null($value)) {
            return $value;
        }

        switch ($this->getCastType($key)) {
            case 'qiniu':
                if (stripos($value, 'http') === FALSE) {
                    return QiniuUtil::buildQiniuUrl($value);
                } else {
                    return $value;
                }
            case 'qiniu_array':
                return array_map(function ($value) {
                    return QiniuUtil::buildQiniuUrl($value);
                }, $this->fromJson($value));
            case 'amount':
                return $value / 100;
            default:
                return parent::castAttribute($key, $value);
        }
    }

    protected function isJsonCastable($key)
    {
        return $this->hasCast($key) &&
        in_array($this->getCastType($key), ['array', 'json', 'object', 'collection', 'qiniu_array'], true);
    }
}
