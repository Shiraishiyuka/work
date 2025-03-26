<?php

namespace App\Http\Controllers\Admin;

/*use App\Http\Controllers\Controller;*/
use App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use App\Models\User;
/*use Illuminate\Support\Facades\Auth;*/

class StaffListController extends AdminBaseController
{
    public function staff_list(Request $request){

        // リダイレクト処理を呼び出し
    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }

        $users = User::all();
        return view('admin.staff_list',compact('users'));
    }
}
