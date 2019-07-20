<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FilesController extends Controller
{
    //
    public function imagesStore(Request $request)
    {
        $uploadedFile = $request->picture;

        $uploadedFile->store('images');

        return response(['status' => 'success'], 200);

    }

    public function CSVsStore(Request $request)
    {
        $uploadedFile = $request->picture;

        $uploadedFile->store('CSVs');

        return response(['status' => 'success'], 200);

    }
}
