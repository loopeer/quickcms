<?php
/**
 * Created by PhpStorm.
 * User: dengyongbin
 * Date: 2018/3/16
 * Time: 下午3:19
 */
namespace Loopeer\QuickCms\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Loopeer\QuickCms\Jobs\NotifyTemplateJob;
use Loopeer\QuickCms\Models\Api\FormId;
use Loopeer\QuickCms\Models\Backend\NotifyJob;
use Loopeer\QuickCms\Models\Backend\NotifyTemplate;

class NotifyTemplateController extends FastController {

    public function update(Model $model, $id)
    {
        $notifyTemplate = NotifyTemplate::find($id);
        $type = Input::get('type');
        $notifyJob = $this->createNotifyJob($notifyTemplate, $type);
        if ($type == 0) {
            $notifyTestAccount = config('quickCms.notify_test_account');
            $pushCount = $this->batchPush($notifyTestAccount, $notifyTemplate, $notifyJob);
        } else {
            $pushCount = 0;
            FormId::with('account')
                ->where('created_at', '>', Carbon::now()->subWeek())
                ->groupBy('account_id')->chunk(1000, function(Collection $formIds) use ($notifyTemplate, $notifyJob) {
                    $formIds->each(function ($formId, $key) use ($notifyTemplate, $notifyJob, &$pushCount) {
                        $formId->forceDelete();
                        $pushCount++;
                        dispatch(new NotifyTemplateJob($notifyTemplate, $notifyJob, $formId->form_id, $formId->account->{config('quickApi.account_open_id')}));
                    });
                });
        }
        $notifyJob->push_count = $pushCount;
        $notifyJob->save();
        return ['result' => true];
    }

    public function customPush($id)
    {
        return view('backend::notify.custom', compact('id'));
    }

    public function storeCustomPush($id)
    {
        $accountIds = Input::get('accountIds');
        $accountIds = explode(',', $accountIds);
        if (!is_array($accountIds)) {
            return 0;
        }
        $notifyTemplate = NotifyTemplate::find($id);
        $notifyJob = $this->createNotifyJob($notifyTemplate);
        $pushCount = $this->batchPush($accountIds, $notifyTemplate, $notifyJob);
        $notifyJob->push_count = $pushCount;
        $notifyJob->save();
        return 1;
    }

    private function batchPush($accountIds, $notifyTemplate, $notifyJob)
    {
        $pushCount = 0;
        foreach ($accountIds as $accountId) {
            $formId = FormId::where('account_id', $accountId)->where('created_at', '>', Carbon::now()->subWeek())->first();
            if ($formId) {
                $pushCount++;
                $formId->forceDelete();
                dispatch(new NotifyTemplateJob($notifyTemplate, $notifyJob, $formId->form_id, $formId->account->{config('quickApi.account_open_id')}));
            }
        }
        return $pushCount;
    }

    private function createNotifyJob($notifyTemplate, $type = 1)
    {
        $notifyJob = NotifyJob::create([
            'name' => $notifyTemplate->name,
            'data' => $notifyTemplate->data,
            'template_id' => $notifyTemplate->template_id,
            'page' => $notifyTemplate->page,
            'emphasis_keyword' => $notifyTemplate->emphasis_keyword,
            'type' => $type,
        ]);
        return $notifyJob;
    }
}