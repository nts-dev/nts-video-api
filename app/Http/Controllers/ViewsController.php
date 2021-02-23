<?php

namespace App\Http\Controllers;

use App\View;
use Illuminate\Http\Request;

class ViewsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $item = View::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'upload_id' => $request->id
            ],
            [
                'user_id' => $request->user()->id,
                'upload_id' => $request->id
            ]
        );


        if($item->save()){
            $response = response()->json(['message' => 'Success'], 200);
        }else
            $response = response()->json(['error' => 'Error occurred'], 401);
        return $response;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
