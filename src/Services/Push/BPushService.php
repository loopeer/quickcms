<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/12/23
 * Time: 下午1:57
 */
namespace Loopeer\QuickCms\Services\Push;

use Loopeer\Lib\Push\BPush;
use Loopeer\QuickCms\Models\Api\Pushes;
use Loopeer\QuickCms\Services\Utils\LogUtil;

class BPushService {

    protected $apiKey;
    protected $secretKey;
    protected $deployStatus;

    public function __construct() {
        $this->androidApiKey = config('quickcms.baidu_push_android_api_key');
        $this->androidSecretKey = config('quickcms.baidu_push_android_secret_key');
        $this->iosApiKey = config('quickcms.baidu_push_ios_api_key');
        $this->iosSecretKey = config('quickcms.baidu_push_ios_secret_key');
        $this->deployStatus = config('quickcms.baidu_push_sdk_deploy_status');
    }

    public function pushSingleMessage($account_id, $description, $customer_content = array()) {
        $push = Pushes::select('app_channel_id', 'platform')->where('account_id', $account_id)->first();
        if(!isset($push)) {
            return;
        }
        $platform = $push->platform;
        if($platform == 'android') {
            $bPush = self::getBPush('android');
        } else {
            $bPush = self::getBPush('ios');
        }
        $result = $bPush->pushSingleMessage($push->app_channel_id, $description, $customer_content);
        self::printResult($platform, $result);
    }

    public function pushBatchMessage($accountIds, $description, $customerContent = array()) {
        $pushes = Pushes::select('app_channel_id', 'platform')->whereIn('account_id', $accountIds)->get();
        $androidChannelIds = array();
        $iosChannelIds = array();
        foreach($pushes as $push) {
            $appChannelId = $push->app_channel_id;
            if($push->platform == 'android') {
                $androidChannelIds[] = $appChannelId;
            } else {
                $iosChannelIds[] = $appChannelId;
            }
        }
        if(isset($androidChannelIds)) {
            $androidPush = self::getBPush('android');
            $result = $androidPush->pushBatchMessage($androidChannelIds, $description, $customerContent);
            self::printResult('android', $result);
        }
        if(isset($iosChannelIds)) {
            $iosPush = self::getBPush('ios');
            $result = $iosPush->pushBatchMessage($iosChannelIds, $description, $customerContent);
            self::printResult('ios', $result);
        }
    }

    public function pushAllMessage($description, $customer_content = array()) {
        $androidPush = self::getBPush('android');
        $iosPush = self::getBPush('ios');
        $androidResult = $androidPush->pushAllMessage($description, $customer_content);
        self::printResult('android', $androidResult);
        $iosResult = $iosPush->pushAllMessage($description, $customer_content);
        self::printResult('ios', $iosResult);
    }

    private function getBPush($platform) {
        if($platform == 'android') {
            $bPush = new BPush($this->androidApiKey, $this->androidSecretKey);
        } else {
            $bPush = new BPush($this->iosApiKey, $this->iosSecretKey, 1, $this->deployStatus);
        }
        return $bPush;
    }

    /**
     * 打印推送结果
     * @param $platform
     * @param $result
     */
    private function printResult($platform, $result) {
        $logger = LogUtil::getLogger('push', 'push');
        $logger->addInfo('platform = ' . $platform . ' result = ' . $result);
    }
}