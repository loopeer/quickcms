<?php

namespace Loopeer\QuickCms\Models\Backend;

use Illuminate\Support\Str;

class FastModel extends BaseModel
{

    protected $buttons;
    protected $index;
    protected $route;
    protected $create;
    protected $createHidden = [];
    protected $detail;

    public function buttons()
    {
        $buttons = ['create' => true, 'dbExport' => false, 'queryExport' => false, 'edit' => true, 'detail' => true,
            'delete' => true, 'actions' => [], 'style' => true];
        if (!isset($this->buttons)) {
            return $buttons;
        }

        foreach ($this->buttons as $name => $value) {
            $buttons[$name] = $value;
        }
        return $buttons;
    }

    public function getRoute()
    {
        if (isset($this->route)) {
            return $this->route;
        }
        return str_replace('\\', '', Str::snake(Str::plural(class_basename($this))));
    }

    public function __set($key, $value)
    {
        $this->$key = $value;
    }

    public function __get($key)
    {
        switch($key) {
            case 'index':
                return $this->index;
            case 'buttons':
                return $this->buttons();
            case 'route':
                return $this->getRoute();
            case 'create':
                return $this->create;
            case 'createHidden':
                return $this->createHidden;
            case 'detail':
                return $this->detail;
            default:
                return parent::__get($key);
        }
    }
}
