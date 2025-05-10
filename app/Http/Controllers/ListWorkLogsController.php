<?php

namespace App\Http\Controllers;

use App\Models\WorkLog;

class ListWorkLogsController extends Controller
{
    public function __invoke()
    {
        return view('list_work_logs', [
            'workLogs' => WorkLog::paginate(20),
        ]);
    }
}
