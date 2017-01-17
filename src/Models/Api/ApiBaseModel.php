<?php

namespace Loopeer\QuickCms\Models\Api;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Loopeer\QuickCms\Services\Utils\QiniuUtil;

abstract class ApiBaseModel extends Model
{
    use SoftDeletes;

    const NO = 0;
    const YES = 1;

    const STATUS_OFFLINE = 0;
    const STATUS_ONLINE = 1;

    const STATUS_NORMAL = 0;
    const STATUS_DISABLE = 1;

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
    ];

    public function scopeNormal($query)
    {
        return $query->where('status', self::STATUS_NORMAL);
    }

    public function scopeOnline($query)
    {
        return $query->where('status', self::STATUS_ONLINE);
    }

    public function scopeSort($query, $direction = 'asc')
    {
        return $query->orderBy('sort', $direction);
    }

    public function scopeStatus($query, $value = 0)
    {
        if (is_array($value))
        {
            $query->whereIn('status', $value);
        } else
        {
            $query->where('status', $value);
        }
    }

    public function scopeAccountId($query, $accountId)
    {
        $query->where('account_id', $accountId);
    }

    public function scopeOfType($query, $type)
    {
        $query->where('type', $type);
    }

    public function scopeWheres($query, array $wheres)
    {
        foreach ($wheres as $column => $value) {
            $query->where($column, $value);
        }
    }

    protected function serializeDate(DateTime $date)
    {
        return $date->getTimestamp();
    }

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

    public function account()
    {
        return $this->belongsTo('model_bind.account');
    }
}
