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
    protected $indexColumns;
    protected $orderAbles;
    protected $orderSorts;
    protected $widths;

    public function getButtons()
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

    public function getOperate()
    {
        return [];
    }

    public function getOperateStyle()
    {
        return $this->operateStyle;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function getIndexColumnNames()
    {
        return $this->indexColumnNames;
    }

    public function getIndexColumns()
    {
        return $this->indexColumns;
    }

    public function getOrderAbles()
    {
        return $this->orderAbles;
    }

    public function getOrderSorts()
    {
        return $this->orderSorts;
    }

    public function getWidths()
    {
        return $this->widths;
    }
}
