<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
   public function index()
{
    $logs = ActivityLog::with('users')->latest()->paginate(10);
    return view('admin.activity_logs.index', compact('logs'));
}
}

