<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adjust;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminBaseController;

class CorrectionRequestController extends AdminBaseController
{
    public function correctionrequest(Request $request) {


    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }


        $status = $request->query('status', 'pending');


        $adjustments = Adjust::where('user_id', Auth::id())
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('correctionrequest', compact('adjustments', 'status'));
    }
}
