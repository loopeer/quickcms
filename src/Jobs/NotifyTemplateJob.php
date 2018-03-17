<?php
/**
 * Created by PhpStorm.
 * User: dengyongbin
 * Date: 2018/3/16
 * Time: 下午3:32
 */
namespace Loopeer\QuickCms\Jobs;

use Loopeer\QuickCms\Exceptions\QueueException;
use Loopeer\QuickCms\Models\Backend\NotifyTemplate;
use EasyWeChat\Foundation\Application;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Bus\SelfHandling;

class NotifyTemplateJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $notifyTemplate;
    protected $formId;
    protected $openId;

    public function __construct(NotifyTemplate $notifyTemplate, $formId, $openId) {
        $this->notifyTemplate = $notifyTemplate;
        $this->formId = $formId;
        $this->openId = $openId;
    }

    public function handle()
    {
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
        } catch (\Exception $e) {
            logger($e->getMessage());
            throw new QueueException($e->getMessage(), $e->getCode(), $e);
        }
    }

}