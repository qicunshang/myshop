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
        $list = DB::table('category')->get();
        return apiReturn($list);
    }
}
