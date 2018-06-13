<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\PermissionRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\PermissionRepositoryEloquent as PermissionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
{
    private $notice;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = DB::table('notice')
            ->orderBy('type', 'ASC')
            ->get();
        return view('admin.notice.index', ['list' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $info = (object)['type'=> 3];
        return view('admin.notice.create', ['info' => $info]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = DB::table('notice')->where('id',$id)->first();
        return view('admin.notice.create', ['info' => $info]);
    }

    /**
     * Update the specified resource in storage.
     * @param MenuRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request)
    {
        $notice_img = Input::file('notice_img');
        $params = $request->all();

        $saveData = [
            'title' => $params['title'],
            'titleColor' => $params['titleColor'],
            'type' => $params['type'],
            'url' => $params['url'],
        ];

        if($request->hasFile('notice_img')){
            if($notice_img->isValid()){
                $ext = $notice_img->getClientOriginalExtension();
                if(in_array($ext, ['jpg', 'png', 'gif', 'ico'])){
                    //文件名
                    $fileName = uniqid() . '.' . $ext;
                    $bool = Storage::disk('banner')->put($fileName, file_get_contents($notice_img->getRealPath()));
                }else{
                    return '文件不合法，请上传jpg|png|gif|ico格式的图片！';
                }
                if(!$bool){
                    return '上传图片失败！';
                }else{
                    $saveData['image'] = '/images/banner/' . $fileName;
                }
            }
        }

        if(empty($params['id'])){
            $saveData['created_at'] = date('Y-m-d H:i:s', time());
            DB::table('notice')->insert($saveData);
        }else{
            $saveData['updated_at'] = date('Y-m-d H:i:s', time());
            DB::table('notice')->where('id',$params['id'])->update($saveData);
        }
        return redirect('admin/notice/list');
    }

    public function del($id)
    {
        $res = DB::table('notice')->where('id', $id)->delete();
        if ($res){
            flash('删除成功','success');
        }else{
            flash('删除失败','error');
        }
        return redirect('admin/notice/list');
    }

    public function ajaxIndex(Request $request)
    {
        $result = $this->permission->ajaxIndex($request);
        return response()->json($result,JSON_UNESCAPED_UNICODE);
    }
}
