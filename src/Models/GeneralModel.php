<?php

namespace Loopeer\QuickCms\Models;

//use Illuminate\Database\Eloquent\Model;

class GeneralModel extends BaseModel
{

    protected $buttons;
    protected $operate;
    // default operate button style is down
    protected $operateStyle = true;
    protected $actions;

    protected $index;
    protected $routeName;

    protected $create;
    protected $createHidden;

    protected $detail;

    public function buttons()
    {
        $buttons = ['create' => true, 'edit' => true, 'detail' => true, 'delete' => true, 'dbExport' => false, 'queryExport' => false];
        if (!isset($this->buttons)) {
            return $buttons;
        }

        foreach ($this->buttons as $name => $value) {
            $buttons[$name] = $value;
        }
        return $buttons;
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
            case 'operate':
                return $this->operate;
            case 'operateStyle':
                return $this->operateStyle;
            case 'actions':
                return $this->actions;
            case 'routeName':
                return $this->routeName;
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
