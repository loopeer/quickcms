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

        $pushCount = 0;
        $notifyJob = NotifyJob::create([
            'name' => $notifyTemplate->name,
            'data' => $notifyTemplate->data,
            'template_id' => $notifyTemplate->template_id,
            'page' => $notifyTemplate->page,
            'emphasis_keyword' => $notifyTemplate->emphasis_keyword,
            'type' => $type,
        ]);

        if ($type == 0) {
            $notifyTestAccount = config('quickCms.notify_test_account');
            foreach ($notifyTestAccount as $accountId) {
                $formId = FormId::where('account_id', $accountId)->where('created_at', '>', Carbon::now()->subWeek())->first();
                if ($formId) {
                    $pushCount++;
                    $formId->forceDelete();
                    dispatch(new NotifyTemplateJob($notifyTemplate, $notifyJob, $formId->form_id, $formId->account->mina_open_id));
                }
            }
        } else {
            FormId::with('account')
                ->where('created_at', '>', Carbon::now()->subWeek())
                ->groupBy('account_id')->chunk(1000, function(Collection $formId) use ($notifyTemplate, $notifyJob, &$pushCount) {
                    $formId->forceDelete();
                    $pushCount++;
                    dispatch(new NotifyTemplateJob($notifyTemplate, $notifyJob, $formId->form_id, $formId->account->mina_open_id));
                });
        }

        return ['result' => true];
    }
}