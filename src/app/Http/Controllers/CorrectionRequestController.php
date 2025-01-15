<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CorrectionRequestController extends Controller
{
    public function correctionrequest() {
        return view('correctionrequest');
    }
}
