<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adjust;

class CorrectionRequestController extends Controller
{
    public function correctionrequest() {
        // すべての申請データを取得
        $adjustments = Adjust::orderBy('created_at', 'desc')->get();

        // ビューにデータを渡す
        return view('correctionrequest', compact('adjustments'));
    }
}
