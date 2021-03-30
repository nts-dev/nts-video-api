<?php

namespace App\Http\Controllers;

use App\Http\Commons\Util;
use App\Http\Documents\HSLDocument;
use App\Http\Documents\MediaDocument;
use App\Http\Documents\model\Media;
use App\Http\Documents\ThumbnailDocument;
use App\Http\Documents\WebMDocument;
use Illuminate\Http\Request;
use App\Upload;
use App\Http\Resources\UploadResource;
use Illuminate\Support\Facades\Log;
use Validator, Redirect, Response, File;


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

        $validator = Validator::make($request->all(),
            [
                'subject_id' => 'required',
                'module_id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'file' => 'required|mimes:mp3,mp4,mkv',
            ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 401);
        }


        $row_file = $request->file('file');


        $upload = Upload::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'module_id' => $request->module_id,
            'subject_id' => $request->subject_id,
            'upload_folder_index' => 0,
            'publish' => false,
        ]);

        $document = Upload::find($upload->id);

        if ($document == null)
            return response()->json([
                "success" => true,
                "message" => "Error occurred",
                "file" => null,
            ]);

        if ($files = $row_file) {

            $SUBJECT = Util::generateProjectId($request->subject_id);
            $CATEGORY = $request->module_id;
            $DOCID = $upload->id;

            $PRIMARYPATH = '/media/'. $SUBJECT . "/" . $CATEGORY. "/" . $DOCID;


            $FILE_PATH = 'public/media/' . $SUBJECT . "/" . $CATEGORY . "/" . $DOCID;

//            $STORAGE = public_path('media/');
//
//            dd($STORAGE);


            //store file into document folder
            $file = $request->file->store($FILE_PATH);
            $file_abs = substr($file, 7); //remove 'public' from the path

            $media = new Media($file_abs, $PRIMARYPATH);


            Log::info((array) $media);


            HSLDocument::dispatch($media);
            WebMDocument::dispatch($media);
            ThumbnailDocument::dispatch($media);


//            store your file into database

            $document->disk = $FILE_PATH;
            $document->raw_link = $file;
            $document->time_encoded = now();
            $document->save();

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $document,
                // "type" => $mimeType,
            ]);

        }
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
