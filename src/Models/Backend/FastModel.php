<?php

namespace Loopeer\QuickCms\Models\Backend;

class FastModel extends BaseModel
{
    protected $buttons;
    protected $index;
    protected $where;
    protected $create;
    protected $createHidden = [];
    protected $detail;
    protected $route;
    protected $module;
    protected $redirect_column;
    protected $redirect_back_route;
    protected $state_save = false;
    protected $business_id = null;

    public function buttons()
    {
        $buttons = ['create' => true, 'dbExport' => false, 'queryExport' => false, 'edit' => true, 'detail' => true,
            'delete' => true, 'actions' => [], 'style' => true, 'customs' => []];
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
        return str_replace('\\', '', camel_case(str_plural(class_basename($this))));

    }

    public function getModule()
    {
        return $this->module ?: $this->getRoute();
    }

    public function __get($key)
    {
        switch($key) {
            case 'index':
                return $this->index;
            case 'where':
                return $this->where;
            case 'buttons':
                return $this->buttons();
            case 'create':
                return $this->create;
            case 'createHidden':
                return $this->createHidden;
            case 'detail':
                return $this->detail;
            case 'route':
                return $this->getRoute();
            case 'module':
                return $this->getModule();
            case 'redirect_column':
                return $this->redirect_column;
            case 'redirect_back_route':
                return $this->redirect_back_route;
            case 'state_save':
                return $this->state_save;
            case 'business_id':
                return $this->business_id;
            default:
                return parent::__get($key);
        }
    }
}
