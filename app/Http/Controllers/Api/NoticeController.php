<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    //
    public function noticeList(Request $request)
    {
        $list = DB::table('notice')->get();
        return apiReturn($list);
    }
}
