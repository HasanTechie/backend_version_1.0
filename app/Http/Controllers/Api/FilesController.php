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
            $file=  $uploadedFile->store('images');

            $fileName = $uploadedFile->getClientOriginalName();
            $fileURL = Storage::url($file);
            $fileType = pathinfo($file)['extension'];
            $userID = $request->user()->id;

        }

        return response(['status' => 'success'], 200);

    }

    public function CSVsStore(Request $request)
    {
        $uploadedFiles = $request->CSVs;

        foreach ($uploadedFiles as $uploadedFile) {
           $file= $uploadedFile->store('CSVs');

            $fileName = $uploadedFile->getClientOriginalName();
            $fileURL = Storage::url($file);
            $fileType = pathinfo($file)['extension'];
            $userID = $request->user()->id;
        }

        return response(['status' => 'success'], 200);

    }
}
