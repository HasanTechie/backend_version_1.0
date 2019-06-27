<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FilesController extends Controller
{
    //
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = 'KuKMQbgZPv0PRC6GqCMlDQ7fgdamsVY75FrQvHfoIbw4gBaG5UX0wfk6dugKxrtW';
    }

    public function imagesStore(Request $request)
    {
        $uploadedFiles = $request->images;

        foreach ($uploadedFiles as $uploadedFile) {
            $file = $uploadedFile->store('images');

            $fileName = $uploadedFile->getClientOriginalName();
            $fileURL = Storage::url($file);
            $fileType = pathinfo($file)['extension'];
            $userID = $request->user()->id;


            DB::table('files')->insert([
                'user_id' => $userID,
                'file_name' => $fileName,
                'file_url' => $fileURL,
                'file_type' => $fileType,
                'uploaded_by' => 'user',
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);

        }

        return response(['status' => 'success'], 200);

    }

    public function CSVsStore(Request $request)
    {
        $uploadedFiles = $request->CSVs;

        foreach ($uploadedFiles as $uploadedFile) {

            $file = $uploadedFile->storeAs('CSVs', \Str::random(40) . '.' . $uploadedFile->getClientOriginalExtension());
            $fileName = $uploadedFile->getClientOriginalName();
            $fileURL = Storage::url($file);
            $fileType = pathinfo($file)['extension'];
            $userID = $request->user()->id;

            DB::table('files')->insert([
                'user_id' => $userID,
                'file_name' => $fileName,
                'file_url' => $fileURL,
                'file_type' => $fileType,
                'uploaded_by' => 'user',
                'created_at' => DB::raw('now()'),
                'updated_at' => DB::raw('now()')
            ]);
        }

        return response(['status' => 'success'], 200);

    }

    public function ResponseCSVsStore(Request $request, $apiKey)
    {

        if ($apiKey == $this->apiKey) {

            $uploadedFiles = $request->CSVs;
            $userID = $request->user_id;

            foreach ($uploadedFiles as $uploadedFile) {

                $file = $uploadedFile->storeAs('CSVs', \Str::random(40) . '.' . $uploadedFile->getClientOriginalExtension());
                $fileName = $uploadedFile->getClientOriginalName();
                $fileURL = Storage::url($file);
                $fileType = pathinfo($file)['extension'];

                DB::table('files')->insert([
                    'user_id' => $userID,
                    'file_name' => $fileName,
                    'file_url' => $fileURL,
                    'file_type' => $fileType,
                    'uploaded_by' => 'ml_algorithm',
                    'created_at' => DB::raw('now()'),
                    'updated_at' => DB::raw('now()')
                ]);
            }

            return response(['status' => 'success'], 200);

        } else {
            dd('Error: Incorrect API Key');
        }
    }
}
