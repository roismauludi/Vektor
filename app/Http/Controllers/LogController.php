<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanLog;

class LogController extends Controller
{
    public function logaktivitas()
    {
        $logs = PengajuanLog::all();
        return view('log', compact('logs'));
    }
}
