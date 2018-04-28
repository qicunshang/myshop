<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    //
    public function categoryList(Request $request)
    {
        $params = $request->all();
        $list = DB::table('category')
            ->where(function($query) use($params){
                if(isset($params['cStatus'])){
                    $query->where('cStatus', $params['cStatus']);
                }
            })
            ->orderBy('cNo', 'asc')
            ->get();
        return apiReturn($list);
    }
}
