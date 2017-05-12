<?php

namespace Loopeer\QuickCms\Services\Utils;

use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class QiniuCloud
{
	protected $domain = null;
	protected $bucket = null;
	protected $accessKey = null;
	protected $secretKey = null;

	public function __construct($bucket = null)
	{
		$this->domain = config('quickCms.qiniu_url');
		$this->bucket = $bucket ?: config('quickCms.qiniu_bucket');
		$this->accessKey = config('quickCms.qiniu_access_key');
		$this->secretKey = config('quickCms.qiniu_secret_key');
	}

	/**
	 * upload local file to qiniu cloud
	 * @param $key
	 * @param $file
	 * @return mixed
	 */
	public function upload($key = null, $file)
	{
		$uploadToken = $this->getToken();
		$uploadMgr = new UploadManager();
		list($ret, $err) = $uploadMgr->putFile($uploadToken, $key, $file);
		if ($err !== null) {
			return $err;
		} else {
			return $ret;
		}
	}

    /**
     * fetch third-party file to qiniu cloud
     * @param $key
     * @param $url
     * @return mixed
     */
    public function fetch($key, $url)
    {
        $auth = new Auth($this->accessKey, $this->secretKey);
        $bmgr = new BucketManager($auth);
        list($ret, $err) = $bmgr->fetch($url, $this->bucket, $key);
        if ($err !== null) {
            return $err;
        } else {
            return $ret;
        }
    }

    public function fetchPrivate($key, $url)
    {
        $secret_key = config('quickCms.qiniu_sg_secret_key');
        $public_key = config('quickCms.qiniu_sg_access_key');
        $this->secretKey = isset($secret_key) ? $secret_key : config('quickCms.qiniu_secret_key');
        $this->accessKey = isset($public_key) ? $public_key : config('quickCms.qiniu_access_key');
        $auth = new Auth($this->accessKey, $this->secretKey);
        $bmgr = new BucketManager($auth);
        list($ret, $err) = $bmgr->fetch($url, $this->bucket, $key);
        if ($err !== null) {
            return $err;
        } else {
            return $ret;
        }
    }

	/**
	 * download from qiniu cloud
	 * @param $key
	 * @return string
	 */
	public function download($key)
	{
		$auth = new Auth($this->accessKey, $this->secretKey);
		$baseUrl = \Qiniu\entry($this->bucket, $key);
		$privateUrl = $auth->privateDownloadUrl($baseUrl);
		return $privateUrl;
	}

	/**
	 * Get qiniu upload token
	 * @return string
	 */
	public function getToken()
	{
		$policy = [
			'scope' => $this->bucket,
			'deadline' => time() + 604800, // 7 * 24 * 3600
		];
		$mac = new \Qiniu\Mac($this->accessKey, $this->secretKey);
		$upToken = $mac->signWithData(json_encode($policy));
		return $upToken;
	}

}