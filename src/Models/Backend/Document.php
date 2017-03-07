<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: WangKaiBo
 * Date: 16/1/26
 * Time: 上午11:04
 */
namespace Loopeer\QuickCms\Models\Backend;

class Document extends FastModel
{
    protected $index = [
        ['column' => 'id'],
        ['column' => 'key'],
        ['column' => 'title'],
        ['column' => 'created_at'],
        ['column' => 'updated_at'],
    ];

    protected $create = [
        ['column' => 'key', 'rules' => ['required' => true]],
        ['column' => 'title'],
        ['column' => 'content', 'type' => 'editor'],
    ];

    protected $detail = [
        ['column' => 'id'],
        ['column' => 'key'],
        ['column' => 'title'],
        ['column' => 'content', 'type' => 'html'],
        ['column' => 'created_at'],
        ['column' => 'updated_at'],
    ];
    protected $module = '文档';
}
