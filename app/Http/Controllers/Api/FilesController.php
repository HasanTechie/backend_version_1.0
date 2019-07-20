<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    //
    public function imagesStore(Request $request)
    {
        $uploadedFiles = $request->images;

        foreach ($uploadedFiles as $uploadedFile) {
            $fileName=  $uploadedFile->store('images');

            dd(Storage::url($fileName));

        }

        return response(['status' => 'success'], 200);

    }

    public function CSVsStore(Request $request)
    {
        $uploadedFiles = $request->CSVs;

        foreach ($uploadedFiles as $uploadedFile) {
           $fileName= $uploadedFile->store('CSVs');

            dd(Storage::url($fileName));
        }

        return response(['status' => 'success'], 200);

    }
}
