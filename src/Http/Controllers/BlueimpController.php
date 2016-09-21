<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: YuGang Yang
 * Date: 11/27/14
 * Time: 17:29
 */

namespace Loopeer\QuickCms\Http\Controllers;

use Config;
use Input;
use Log;
use Session;
use DB;
use Redirect;
use Response;
use Exception;

class BlueimpController extends BaseController {

    public function getImage() {
        $url = Input::get('url');
        if (strpos($url, config('quickcms.qiniu_url')) !== false) {
            $key = str_replace(config('quickcms.qiniu_url') . '/', '', $url);
        } else {
            $key = $url;
        }
        if (isset($key)) {
            if (strpos($key, 'http') !== false) {
                $size = null;
                $thumbnailUrl = $url;
            } else {
                $size = json_decode(file_get_contents($url . '?stat'))->fsize;
                $thumbnailUrl = $url . '?imageView2/2/w/200/h/100';
            }
            $success = new \stdClass();
            $success->name = $key;
            $success->size = $size;
            $success->url = $url;
            $success->thumbnailUrl = $thumbnailUrl;

            // Remove the file from qiniu when invoke the delete action
            $success->deleteUrl = route('admin.blueimp.delete', 1);// 处理删除的action
            $success->deleteType = 'GET';
            $success->key = $key;
            return Response::json(array('files' => array($success)), 200);
        }
    }

    public function upload() {

        $qiniu = \Qiniu\Qiniu::create(array(
            'access_key' => config('quickcms.qiniu_access_key'),
            'secret_key' => config('quickcms.qiniu_secret_key'),
            'bucket' => config('quickcms.qiniu_bucket')
        ));

        $file_name = Input::get('file_name');

        try{
            $image = Input::file($file_name);
            $upload_key = $file_name.'_'.date('YmdHis',time()).rand(1,9999);
            $photo = $qiniu->uploadFile($image->getRealPath(), $upload_key);

            $key = $photo->data['key'];
            $url = config('quickcms.qiniu_url').'/'.$key;
            $thumbnailUrl = $url . '?imageView2/2/w/200/h/100';

            $success = new \stdClass();
            $success->name = $key;
            $success->size = json_decode(file_get_contents($url . '?stat'))->fsize;
            $success->url = $url;
            $success->thumbnailUrl = $thumbnailUrl;
            $success->photo_name='';
            // TODO
            // Remove the file from qiniu when invoke the delete action

            $success->deleteUrl = route('admin.blueimp.delete', 1);// 处理删除的action
            $success->deleteType = 'GET';
            $success->key = $key;
            return Response::json(array( 'files'=> array($success)), 200);
        }catch (Exception $e){
            $error = new \stdClass();
            $error->error=$e->getMessage();
            $error->deleteUrl = route('admin.blueimp.delete', 'undefined');// 处理删除的action
            $error->deleteType = 'GET';
            return Response::json(array( 'files'=> array($error)), 200);
        }


    }

    public function destroy() {
        $success = new \stdClass();
        return Response::json(array('files'=> array($success)), 200);
    }

} 