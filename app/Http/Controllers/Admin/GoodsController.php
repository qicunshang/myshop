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

class GoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = DB::table('goods')
            ->select('goods.*', 'category.cName', DB::raw('group_concat(goods_img.imgUrl) as imgUrl'))
            ->join('category', 'goods.cId', '=', 'category.id')
            ->leftJoin('goods_img', 'goods_img.gid', '=', 'goods.id')
            ->where('category.cStatus', 1)
            ->groupBy('goods.id')
            ->orderBy('created_at', 'DESC')
            ->orderBy('updated_at', 'ASC')
            ->paginate(10);
        foreach($list as $key=>$item){
            $list[$key]->imgUrl = explode(',', $item->imgUrl);
        }
        return view('admin.goods.index', ['list' => $list]);
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
        return view('admin.goods.create', ['info' => $info, 'category' =>$category]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = DB::table('goods')->where('id',$id)->first();
        $category = DB::table('category')
            ->orderBy('cNo', 'asc')
            ->where('cNo', '<>', '0')
            ->get();
//        dd($info);
        return view('admin.goods.create', ['info' => $info, 'category' =>$category]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request)
    {
        $gMovie = Input::file('gMovie');
        $params = $request->all();

        //获取登录中的用户信息
        $user =  Auth::guard('admin')->user();

        $saveData = [
            'gName'         => $params['gName'],
            'gDesc'         => $params['gDesc'],
            'cId'           => $params['cId'],
            'price'         => $params['price'],
            'tradePrice'    => $params['tradePrice'],
            'region'        => $params['region'],
            'freightAmount' => $params['freightAmount'],
            'hot'           => $params['hot'],
            'package'       => $params['package'],
            'gStatus'       => $params['gStatus'],
            'stock'         => $params['stock'],
            'author'        => $user->name,
            'author_id'     => $user->id,
        ];

        if($request->hasFile('gMovie')){
            if($gMovie->isValid()){
                $ext = $gMovie->getClientOriginalExtension();
                $allow_ext = ['jpg', 'png', 'gif', 'ico', 'mp4', 'avi', 'flv', '3gp', 'rmvb', 'wmv', 'mpeg'];
                if(in_array($ext, $allow_ext)){
                    //文件名
                    $fileName = uniqid() . '.' . $ext;
                    $bool = Storage::disk('goods')->put($fileName, file_get_contents($gMovie->getRealPath()));
                }else{
                    $allow_ext_string = '';
                    foreach($allow_ext as $item){
                        $allow_ext_string .= $item . '|';
                    }
                    return '文件不合法，请上传' . rtrim($allow_ext_string, '|') . '格式的文件！';
                }
                if(!$bool){
                    return '上传图片失败！';
                }else{
                    $saveData['gMovie'] = '/images/goods/' . $fileName;
                }
            }
        }

        if(empty($params['id'])){
            $saveData['created_at'] = date('Y-m-d H:i:s', time());
            DB::table('goods')->insert($saveData);
        }else{
            $saveData['updated_at'] = date('Y-m-d H:i:s', time());
            DB::table('goods')->where('id',$params['id'])->update($saveData);
        }
        return redirect('admin/goods/list');
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
