<?php

namespace Loopeer\QuickCms\Models;


use Illuminate\Database\Eloquent\Model;

class GeneralModel extends Model
{

    protected $buttons;
    protected $operate;
    // default operate button style is down
    protected $operateStyle = true;
    protected $actions;
    protected $indexColumnNames;
    protected $indexColumnRenames;
    protected $indexColumns;
    protected $orderAbles;
    protected $orderSorts;
    protected $widths;
    protected $routeName;

    protected $query;

    public function buttons()
    {
        $buttons = ['create' => true, 'edit' => true, 'show' => true, 'delete' => true, 'dbExport' => false, 'queryExport' => false];
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
            case 'indexColumnNames':
                return $this->indexColumnNames;
            case 'indexColumnRenames':
                return $this->indexColumnRenames;
            case 'indexColumns':
                return $this->indexColumns;
            case 'buttons':
                return $this->buttons();
            case 'operate':
                return $this->operate;
            case 'operateStyle':
                return $this->operateStyle;
            case 'actions':
                return $this->actions;
            case 'widths':
                return $this->widths;
            case 'orderSorts':
                return $this->orderSorts;
            case 'orderAbles':
                return $this->orderAbles;
            case 'routeName':
                return $this->routeName;
            case 'query':
                return $this->query;
            default:
                return parent::__get($key);
        }
    }
}
