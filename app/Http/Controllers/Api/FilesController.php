<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FilesController extends Controller
{
    //
    public function imagesStore(Request $request)
    {
        $uploadedFiles = $request->images;

        foreach ($uploadedFiles as $uploadedFile) {
            $uploadedFile->store('images');
        }

        return response(['status' => 'success'], 200);

    }

    public function CSVsStore(Request $request)
    {
        $uploadedFiles = $request->CSVs;

        foreach ($uploadedFiles as $uploadedFile) {
            $uploadedFile->store('CSVs');
        }

        return response(['status' => 'success'], 200);

    }
}
