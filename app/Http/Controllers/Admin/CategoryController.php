<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\PermissionRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\PermissionRepositoryEloquent as PermissionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = DB::table('category')->where('cNo', '<>', '0')->get();
//        dd($list);
        return view('admin.category.index', ['list' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $info = (object)[
            'cId'       => 0,
            'hot'       => 0,
            'package'   => 1,
            'gStatus'   => 0,
        ];
        $category = DB::table('category')
            ->orderBy('cNo', 'asc')
            ->where('cNo', '<>', '0')
            ->get();
        return view('admin.category.create', ['info' => $info, 'category' =>$category]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = DB::table('category')
            ->where('id',$id)
            ->first();
//        dd($info);
        return view('admin.category.create', ['info' => $info]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request)
    {
        $params = $request->all();

        $saveData = [
            'cNo'       => $params['cNo'],
            'cName'     => $params['cName'],
            'iconUrl'   => $params['iconUrl'],
            'cStatus'   => $params['cStatus'],
        ];

        DB::table('category')
            ->where('id',$params['id'])
            ->update($saveData);
        return redirect('admin/category/list');
    }

    public function del($id)
    {
        $res = DB::table('category')
                ->where('id', $id)
                ->delete();
        if ($res){
            flash('删除成功','success');
        }else{
            flash('删除失败','error');
        }
        return redirect('admin/category/list');
    }

    public function ajaxIndex(Request $request)
    {
        $result = $this->permission->ajaxIndex($request);
        return response()->json($result,JSON_UNESCAPED_UNICODE);
    }

}
