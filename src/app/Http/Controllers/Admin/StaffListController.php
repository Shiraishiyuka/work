<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StaffListController extends Controller
{
    public function staff_list(){

        $users = User::all();
        return view('admin.staff_list',compact('users'));
    }
}
