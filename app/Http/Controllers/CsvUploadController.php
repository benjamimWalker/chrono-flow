<?php

namespace App\Http\Controllers;

use App\Http\Requests\CsvUploadRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CsvUploadController extends Controller
{
    public function showUploadForm(): View
    {
        return view('upload');
    }

    public function upload(CsvUploadRequest $request)
    {
        return response()->json(['fala' => 'meu']);
    }
}
