<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Competitor as CompetitorResource;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompetitorsAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return CompetitorResource::collection(auth()->user()->competitors()->with('creator')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'user_id' => 'required',
            'hotel_id' => 'required'
        ]);

        if (DB::table('competitors')->where([
            ['user_id', '=', $request->user_id],
            ['hotel_id', '=', $request->hotel_id]
        ])->doesntExist()) {
            $competitor = auth()->user()->competitors()->create($request->all());
            return new CompetitorResource($competitor->load('creator'));
        } else {
            response(['message' => 'Record already exists']);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $hotel_id)
    {
        //
        $deleted = DB::table('competitors')->where([
            ['user_id', '=', $user_id],
            ['hotel_id', '=', $hotel_id]
        ])->delete();

        if ($deleted) {
            return response(['message' => 'Record Deleted']);
        } else {
            return response(['message' => 'No Records Deleted']);
        }
    }
}
