<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 16/1/27
 * Time: 下午4:01
 */
namespace Loopeer\QuickCms\Models;

class General {

    private $model_class;
    private $model_name;
    private $route_name;
    private $index_column;
    private $index_column_format;
    private $index_column_name;
    private $index_column_rename;
    private $index_multi;
    private $index_multi_column;
    private $index_multi_join;
    private $edit_column;
    private $edit_column_name;
    private $edit_column_detail;
    private $actions;
    private $create_able;
    private $model;
    private $sort;
    private $where;
    private $edit_hidden;

    public function __construct($general_name) {
        $this->index_column = config($general_name . 'index_column');
        $this->index_column_format = config($general_name . 'index_column_format');
        $this->index_column_name = config($general_name . 'index_column_name');
        $this->index_column_rename = config($general_name . 'index_column_rename', array());
        $this->edit_column = config($general_name . 'edit_column');
        $this->edit_column_name = config($general_name . 'edit_column_name');
        $this->edit_column_detail = config($general_name . 'edit_column_detail');
        $this->model_class = config($general_name . 'model_class');
        $this->model_name = config($general_name . 'model_name');
        $this->actions = config($general_name . 'table_action');
        $this->sort = config($general_name . 'sort');
        $this->where = config($general_name . 'index_where');
        $this->edit_hidden = config($general_name . 'edit_hidden');
        $this->create_able = config($general_name . 'create_able');
        $this->index_multi = config($general_name . 'index_multi');
        $this->index_multi_column = config($general_name . 'index_multi_column');
        $this->index_multi_join = config($general_name . 'index_multi_join');
        $reflectionClass = new \ReflectionClass($this->model_class);
        $this->model = $reflectionClass->newInstance();
    }

    /**
     * @return mixed
     */
    public function getIndexMultiColumn()
    {
        return $this->index_multi_column;
    }

    /**
     * @param mixed $index_multi_column
     */
    public function setIndexMultiColumn($index_multi_column)
    {
        $this->index_multi_column = $index_multi_column;
    }

    /**
     * @return mixed
     */
    public function getModelClass()
    {
        return $this->model_class;
    }

    /**
     * @param mixed $model_class
     */
    public function setModelClass($model_class)
    {
        $this->model_class = $model_class;
    }

    /**
     * @return mixed
     */
    public function getModelName()
    {
        return $this->model_name;
    }

    /**
     * @param mixed $model_name
     */
    public function setModelName($model_name)
    {
        $this->model_name = $model_name;
    }

    /**
     * @return mixed
     */
    public function getRouteName()
    {
        return $this->route_name;
    }

    /**
     * @param mixed $route_name
     */
    public function setRouteName($route_name)
    {
        $this->route_name = $route_name;
    }

    /**
     * @return mixed
     */
    public function getIndexColumn()
    {
        return $this->index_column;
    }

    /**
     * @param mixed $index_column
     */
    public function setIndexColumn($index_column)
    {
        $this->index_column = $index_column;
    }

    /**
     * @return mixed
     */
    public function getIndexColumnFormat()
    {
        return $this->index_column_format;
    }

    /**
     * @param mixed $index_column_format
     */
    public function setIndexColumnFormat($index_column_format)
    {
        $this->index_column_format = $index_column_format;
    }

    /**
     * @return mixed
     */
    public function getIndexColumnName()
    {
        return $this->index_column_name;
    }

    /**
     * @param mixed $index_column_name
     */
    public function setIndexColumnName($index_column_name)
    {
        $this->index_column_name = $index_column_name;
    }

    /**
     * @return mixed
     */
    public function getIndexColumnRename()
    {
        return $this->index_column_rename;
    }

    /**
     * @param mixed $index_column_rename
     */
    public function setIndexColumnRename($index_column_rename)
    {
        $this->index_column_rename = $index_column_rename;
    }

    /**
     * @return mixed
     */
    public function getIndexMulti()
    {
        return $this->index_multi;
    }

    /**
     * @param mixed $index_multi
     */
    public function setIndexMulti($index_multi)
    {
        $this->index_multi = $index_multi;
    }

    /**
     * @return mixed
     */
    public function getIndexMultiJoin()
    {
        return $this->index_multi_join;
    }

    /**
     * @param mixed $index_multi_join
     */
    public function setIndexMultiJoin($index_multi_join)
    {
        $this->index_multi_join = $index_multi_join;
    }

    /**
     * @return mixed
     */
    public function getEditColumn()
    {
        return $this->edit_column;
    }

    /**
     * @param mixed $edit_column
     */
    public function setEditColumn($edit_column)
    {
        $this->edit_column = $edit_column;
    }

    /**
     * @return mixed
     */
    public function getEditColumnName()
    {
        return $this->edit_column_name;
    }

    /**
     * @param mixed $edit_column_name
     */
    public function setEditColumnName($edit_column_name)
    {
        $this->edit_column_name = $edit_column_name;
    }

    /**
     * @return mixed
     */
    public function getEditColumnDetail()
    {
        return $this->edit_column_detail;
    }

    /**
     * @param mixed $edit_column_detail
     */
    public function setEditColumnDetail($edit_column_detail)
    {
        $this->edit_column_detail = $edit_column_detail;
    }

    /**
     * @return mixed
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param mixed $actions
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
    }

    /**
     * @return mixed
     */
    public function getCreateAble()
    {
        return $this->create_able;
    }

    /**
     * @param mixed $create_able
     */
    public function setCreateAble($create_able)
    {
        $this->create_able = $create_able;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return mixed
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * @param mixed $where
     */
    public function setWhere($where)
    {
        $this->where = $where;
    }

    /**
     * @return mixed
     */
    public function getEditHidden()
    {
        return $this->edit_hidden;
    }

    /**
     * @param mixed $edit_hidden
     */
    public function setEditHidden($edit_hidden)
    {
        $this->edit_hidden = $edit_hidden;
    }


}