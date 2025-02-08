<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adjust;


class ApplicationRequestController extends Controller
{
    public function application_request(){

        $adjustments = Adjust::orderBy('created_at', 'desc')->get();

        return view('admin.correction_request', compact('adjustments'));
    }
}
