<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: WangKaiBo
 * Date: 16/1/26
 * Time: 上午10:47
 */
namespace Loopeer\QuickCms\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use View;
use Session;
use Loopeer\QuickCms\Models\Document;
use Response;
use Input;

class DocumentController extends BaseController
{

    public function __construct() {
        $this->middleware('auth.permission:maintenance');
        $this->middleware('auth.permission:admin.document.index');
        parent::__construct();
    }

    public function show() {

    }
    
    public function search() {
        $ret = self::simplePage(['id', 'document_key','title', 'created_at'], new Document());
        return Response::json($ret);
    }

    public function index() {
        $message = Session::get('message');
        return View::make('backend::documents.index', compact('message'));
    }

    public function create() {
        $document = new Document();
        return view('backend::documents.create', compact('document'));
    }

    public function edit($id) {
        $document = Document::find($id);
        return view('backend::documents.create', compact('document'));
    }

    public function store() {
        $data = Input::all();
        unset($data['_token']);
        if (isset($data['id'])) {
            if ($document  = Document::find($data['id'])->update($data)) {
                return Redirect::to('/admin/document')->with('message', array('result'=>true, 'content'=>'编辑成功'));
            }
        }
        if ($document  = Document::create($data)) {
            return Redirect::to('/admin/document')->with('message', array('result'=>true, 'content'=>'添加成功'));
        }
    }

    public function destroy($id) {
        Document::destroy($id);
        return array('result'=>true, 'content'=>'删除成功');
    }

}
