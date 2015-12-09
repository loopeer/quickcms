<?php

/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: YuGang Yang
 * Date: 10/18/14
 * Time: 1:55
 */

namespace Loopeer\QuickCms\Services\Validators;

abstract class Validator {

    protected $data;

    public $errors;

    public static $rules;

    public static $contentRules = [
        'content' => 'required'
    ];

    public static $locationRules = [
        'longitude' => 'required',
        'latitude' => 'required',
    ];

    public function __construct($data = null) {
        $this->data = $data ? : \Input::all();
    }

    public function passes($rules = null) {
        $validation = \Validator::make($this->data, $rules ? : static::$rules);

        if ($validation->passes()) return true;

        $this->errors = $validation->messages();

        return false;
    }

    public function fails() {
        return $this->errors;
    }

    public function messages() {
        return $this->errors;
    }

}