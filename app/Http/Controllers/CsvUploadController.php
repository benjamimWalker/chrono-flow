<?php

namespace App\Http\Controllers;

use App\Actions\BatchStoreWorkLogsFromCsv;
use App\Http\Requests\CsvUploadRequest;
use Illuminate\Contracts\View\View;

class CsvUploadController extends Controller
{
    public function showUploadForm(): View
    {
        return view('upload');
    }

    public function upload(CsvUploadRequest $request, BatchStoreWorkLogsFromCsv $batchStoreWorkLogsFromCsv): void
    {
        $batchStoreWorkLogsFromCsv->handle($request->file('csv'));
    }
}
