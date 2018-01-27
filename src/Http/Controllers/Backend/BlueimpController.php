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

namespace Loopeer\QuickCms\Http\Controllers\Backend;

use Config;
use Illuminate\Support\Facades\File;
use Input;
use Log;
use Loopeer\QuickCms\Services\Utils\QiniuUtil;
use Session;
use DB;
use Redirect;
use Response;
use Exception;

class BlueimpController extends BaseController
{
    public function getResource()
    {
        $url = Input::get('url');
        $file_type = Input::get('file_type', 'image');

        if (strpos($url, config('quickCms.qiniu_url')) !== false) {
            $key = str_replace(config('quickCms.qiniu_url') . '/', '', $url);
            if (strrpos($key, '?') !== false) {
                $key = substr($key, 0, strrpos($key, '?'));
            }
        } else {
            $key = $url;
        }
        if (isset($key)) {
            if (strpos($key, 'http') !== false) {
                $size = null;
                $thumbnailUrl = $url;
            } else {
//                $size = json_decode(file_get_contents($url . '?stat'))->fsize;
//                $thumbnailUrl = $url . '?imageView2/2/w/200/h/100';
                $thumbnailUrl = $url;
            }
            $success = new \stdClass();
            $success->name = $key;
//            $success->size = $size;
            $success->url = $url;
            $success->thumbnailUrl = $thumbnailUrl;

            // Remove the file from qiniu when invoke the delete action
            $success->deleteUrl = route('admin.blueimp.delete', 1);// 处理删除的action
            $success->deleteType = 'GET';
            $success->key = $key;
            return Response::json(array('files' => array($success)), 200);
        }
    }

    public function upload()
    {

        $qiniu = \Qiniu\Qiniu::create(array(
            'access_key' => config('quickCms.qiniu_access_key'),
            'secret_key' => config('quickCms.qiniu_secret_key'),
            'bucket' => config('quickCms.qiniu_bucket')
        ));

        $file_name = Input::get('file_name');

        try{
            $image = Input::file($file_name);
            $upload_key = $file_name.'_'.date('YmdHis',time()).rand(1,9999);
            $photo = $qiniu->uploadFile($image->getRealPath(), $upload_key);

            $key = $photo->data['key'];
//            $url = config('quickcms.qiniu_url').'/'.$key;
//            $thumbnailUrl = $url . '?imageView2/2/w/200/h/100';
            $thumbnailUrl = QiniuUtil::buildQiniuThumbnail($key);
            $url = QiniuUtil::buildQiniuUrl($key);

            $success = new \stdClass();
            $success->name = $key;
//            $success->size = json_decode(file_get_contents($url . '?stat'))->fsize;
            $success->url = $url;

            $success->photo_name='';
            $success->$thumbnailUrl = $thumbnailUrl;

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

    public function uploadVoice()
    {

        $qiniu = \Qiniu\Qiniu::create(array(
            'access_key' => config('quickCms.qiniu_access_key'),
            'secret_key' => config('quickCms.qiniu_secret_key'),
            'bucket' => config('quickCms.qiniu_bucket')
        ));

        $file_name = Input::get('file_name');

        try{
            $image = Input::file($file_name);
            $upload_key = $file_name.'_'.date('YmdHis',time()).rand(1,9999);
            $photo = $qiniu->uploadFile($image->getRealPath(), $upload_key);

            $key = $photo->data['key'];
//            $url = config('quickcms.qiniu_url').'/'.$key;
//            $thumbnailUrl = $url . '?imageView2/2/w/200/h/100';
            $thumbnailUrl = QiniuUtil::buildQiniuThumbnail($key);
            $url = QiniuUtil::buildQiniuUrl($key);

            $success = new \stdClass();
            $success->name = $key;
//            $success->size = json_decode(file_get_contents($url . '?stat'))->fsize;
            $success->url = $url;

            $success->photo_name='';
            $success->duration = json_decode(file_get_contents($url . '?avinfo'))->format->duration * 100;
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

    public function destroy()
    {
        $success = new \stdClass();
        return Response::json(array('files'=> array($success)), 200);
    }

    public function upload4Local()
    {
        $file_name = Input::get('file_name');
        try {
            $file = Input::file($file_name);
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $upload_name = $file_name . '_' . date('YmdHis', time()) . rand(1,9999) . '.' . $extension;
            $path = public_path() . '/loopeer/upload';
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $file->move($path, $upload_name);
            $result = ['result' => true, 'path' => '/loopeer/upload/' . $upload_name, 'url' => url() . '/loopeer/upload/' . $upload_name];
        } catch (Exception $e) {
            $result = ['result' => false];
        }
        return $result;
    }

} 
