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
            $image = Input::file('file');
            $upload_key = 'file' . '_'.date('YmdHis',time()).rand(1,9999);
            $photo = $qiniu->uploadFile($image->getRealPath(), $upload_key);
            $key = $photo->data['key'];
            $url = config('quickcms.qiniu_url').'/'.$key;
            $ret = ['result' => true, 'key' => $key, 'url' => $url, 'msg' => '上传成功'];
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
                $files[] = ['name' => $key, 'size' => $size, 'key' => $key, 'url' => $url, 'mime_type' => $mime_type];
            }
        }
        return $files;
    }
}
