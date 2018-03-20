<?php
/**
 * Created by PhpStorm.
 * User: dengyongbin
 * Date: 2018/3/16
 * Time: 下午3:32
 */
namespace Loopeer\QuickCms\Jobs;

use Loopeer\QuickCms\Exceptions\QueueException;
use Loopeer\QuickCms\Models\Backend\NotifyJob;
use Loopeer\QuickCms\Models\Backend\NotifyTemplate;
use EasyWeChat\Foundation\Application;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Loopeer\QuickCms\Services\Utils\LogUtil;

class NotifyTemplateJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $notifyTemplate;
    protected $notifyJob;
    protected $formId;
    protected $openId;

    public function __construct(NotifyTemplate $notifyTemplate, NotifyJob $notifyJob, $formId, $openId) {
        $this->notifyTemplate = $notifyTemplate;
        $this->notifyJob = $notifyJob;
        $this->formId = $formId;
        $this->openId = $openId;
    }

    public function handle()
    {
        $logger = LogUtil::getLogger('push', 'notify_push');

        $notifyTemplate = $this->notifyTemplate;
        $templateData = [
            'touser' => $this->openId,
            'form_id' => $this->formId,
            'template_id' => $notifyTemplate->template_id,
            'page' => $notifyTemplate->page,
            'data' => json_decode($notifyTemplate->data, true),
            'emphasis_keyword' => $notifyTemplate->emphasis_keyword ?: null
        ];

        try {
            $app = new Application(config('weapp.options'));
            $app->mini_program->notice->send($templateData);
            $this->notifyJob->increment('real_count');
        } catch (\Exception $e) {
            $logger->addError($e->getMessage());
        }
    }

}