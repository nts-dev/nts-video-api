<?php

namespace App\Http\Controllers;

use App\Http\Resources\MyUploadsResource;
use App\Http\Resources\SavedItemsResource;
use App\Http\Resources\UploadResource;
use App\Http\Resources\UserProfileResource;
use App\SavedItem;
use App\Upload;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavedItemsController extends Controller
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
    public function index(Request $request)
    {
        return SavedItemsResource::collection(SavedItem::where('user_id', '=', $request->user()->id)->paginate(10));
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
        $item = SavedItem::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'upload_id' => $request->id
            ],

            [
                'user_id' => $request->user()->id,
                'upload_id' => $request->id
            ]
        );

        if ($item->save())
           return   new SavedItemsResource($item);


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new MyUploadsResource(SavedItem::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request, $id)
    {
        $upload = SavedItem::find($id);

        if($upload->delete())
            return response()->json(['code' => '204',
                'message' => "Success",
                'data' => null]);
        else
            return response()->json(['code' => '400',
                'message' => "Fail.",
                'data' => null]);

    }

}
