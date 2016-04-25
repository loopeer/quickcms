<?php

namespace Loopeer\QuickCms\Http\Controllers;

use App\Http\Requests;
use Input;
use Exception;
use DB;
use Loopeer\QuickCms\Services\Utils\QiniuUtil;

class DropzoneController extends BaseController
{
    public function upload() {
        $qiniu = \Qiniu\Qiniu::create(array(
            'access_key' => config('quickcms.qiniu_access_key'),
            'secret_key' => config('quickcms.qiniu_secret_key'),
            'bucket' => config('quickcms.qiniu_bucket')
        ));

        try {
            $file = Input::file('file');
            $file_name = $file->getClientOriginalName();
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $upload_key = 'file' . '_'.date('YmdHis',time()).rand(1,9999);
            $real_key = $upload_key  . '.' . $extension;
            $upload = $qiniu->uploadFile($file->getRealPath(), $real_key);
            $real_key = $upload->data['key'];
            $url = config('quickcms.qiniu_url') . '/' . $real_key;
            $ret = ['result' => true, 'key' => $upload_key, 'real_key' => $real_key, 'url' => $url, 'msg' => '上传成功'];
        } catch(Exception $e) {
            $ret = ['result' => false, 'msg' => '上传失败'];
        }
        return $ret;
    }

    public function fileList($id) {
        $table = Input::get('table');
        $column = Input::get('column');
        $row = DB::table($table)->find($id);
        $keys = !empty($row->$column) ? explode(',',  $row->$column) : null;
        $files = array();
        if(isset($keys)) {
            foreach($keys as $key) {
                $url = QiniuUtil::buildQiqiuImageUrl($key);
                $file_info = json_decode(file_get_contents($url . '?stat'));
                $size = $file_info->fsize;
                $mime_type = $file_info->mimeType;
                $upload_key = strstr($key, '.', true);
                $files[] = ['name' => $key, 'size' => $size, 'key' => $upload_key, 'url' => $url, 'mime_type' => $mime_type, 'real_key' => $key];
            }
        }
        return $files;
    }
}
