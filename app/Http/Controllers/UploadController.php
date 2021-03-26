<?php

namespace App\Http\Controllers;

use App\Http\Commons\Util;
use Illuminate\Http\Request;
use App\Upload;
use App\Http\Resources\UploadResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class UploadController extends Controller
{

    public $successStatus = 200;
    public $createdStatus = 201;

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
        return UploadResource::collection(Upload::with(['views', 'subtitle']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {


//        if (!$request->hasFile('media'))
//            return response()->json(['data' => null, 'error' => 'Request has no media file'], 403);


        $upload = Upload::create([
            'user_id' => $request->user()->id,
//            'user_id' => 3,
            'title' => $request->title,
            'description' => $request->description,
            'module_id' => $request->module_id,
            'subject_id' => $request->subject_id,
            'upload_folder_index' => 0,
            'publish' => false,
            'thumbnailLink' => '/thumbnail_main.jpg',
            'videoLink' => '/manifest.m3u',
        ]);

        return response()->json([
            "success" => true,
            "message" => "File successfully uploaded",
            "Upload" => $upload,
        ]);


    }



    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return new UploadResource(Upload::find($id));

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
        $upload = Upload::find($id);
        if ($request->user()->id !== $upload->user_id) {
            return response()->json(['error' => 'You can only edit your own uploads.'], 403);
        }

        $upload->update($request->only(['title', 'description', 'publish']));

        return new UploadResource($upload);
    }

    /**
     * @param Request $request
     * @param $subject_is
     * @return UploadResource|\Illuminate\Http\JsonResponse
     *
     * Returns uploads per subject
     *
     */


    public function uploadsInSubject($id)
    {
        return UploadResource::collection(Upload::with(['views', 'subtitle'])->where('subject_id', '=', $id)->get());
    }


    public function uploadsInCategory($id)
    {
        return UploadResource::collection(Upload::with(['views', 'subtitle'])->where('module_id', '=', $id)->get());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $upload = Upload::find($id);
        if ($request->user()->id !== $upload->user_id) {
            return response()->json(['error' => 'You are not allowed to remove this.'], 403);
        }

        $upload->delete();

        return response()->json(null, 204);
    }
}
