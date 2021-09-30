<?php

namespace App\Http\Controllers;

use App\Http\Commons\HashCode;
use App\Http\Commons\Util;
use App\Http\Documents\HSLDocument;
use App\Http\Documents\model\Media;
use App\Http\Documents\ThumbnailDocument;
use App\Http\Documents\WebMDocument;
use App\Http\Resources\UploadResource;
use App\Upload;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Redirect;
use Response;
use Validator;

//use Kreait\Firebase\Storage;


class UploadController extends Controller
{

    public $successStatus = 200;
    public $createdStatus = 201;

    public function __construct()
    {
        //$this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return UploadResource::collection(Upload::orderBy('id', 'desc')
            ->get());

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
                //'file' => 'required|mimes:mp3,mp4,mkv,h5p',
            ]);


//        Log::info((array) $validator);

        if ($validator->fails()) {
            return response()->json(['state' => false, 'message' => $validator->errors()], 401);
        }


//        return response()->json(['state' => true, 'message' => 'success'], 201);

        $row_file = $request->file('file');

        $upload = Upload::create([
            'user_id' => 1, //$request->user()->id
            'title' => $request->title,
            'description' => $request->description,
            'module_id' => $request->module_id,
            'subject_id' => $request->subject_id,
            'upload_folder_index' => 0,
            'publish' => false,
            'hash' => HashCode::encrypt($request->title . now()),
        ]);

        $document = Upload::find($upload->id);

        if ($document == null)
            return response()->json([
                "state" => false,
                "message" => "Error occurred",
                "file" => null,
            ]);

        if ($files = $row_file) {

            $SUBJECT = Util::generateProjectId($request->subject_id);
            $CATEGORY = $request->module_id;
            $DOCID = $upload->id;

            $FILE_PATH = 'media/' . $SUBJECT . "/" . $CATEGORY . "/" . $DOCID;

            $extension = $request->file('file')->extension();

            if ($extension == 'zip' || $extension == 'h5p') {

                $originalName = $request->file('file')->getClientOriginalName();

                $path = $request->file('file')->storeAs(
                    $FILE_PATH,
                    $originalName,
                    'public'
                );

                Storage::move($path, $FILE_PATH . "/" . $originalName . ".h5p");

                $document->raw_link = $FILE_PATH . "/" . $originalName . ".h5p";

            } else {

                $path = $request->file->store($FILE_PATH, 'public');
                $document->raw_link = $path;
            }

            //store your file into database
            $document->disk = $FILE_PATH;
            $document->time_encoded = now();
            $document->save();

            return response()->json([
                "state" => true,
                "name" => $document->id,
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
        $upload = Upload::findOrFail($id);
        return new UploadResource($upload);

    }


    /**
     * Display the specified resource.
     *
     * @param string $hashed
     * @return \Illuminate\Http\Response
     */
    public function showByHashedString($hashed)
    {

        $upload = Upload::where('hash', trim($hashed))->first();

        if (!isset($upload->id))
            return response()->json(
                [
                    'state' => false,
                    'error' => 'Requested resource not found.',
                    'data' => $upload,
                ], 403);


        return new UploadResource($upload);

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

        $upload = Upload::findOrFail($id);
//        if ($request->user()->id !== $upload->user_id) {
//            return response()->json(['error' => 'You are not allowed to remove this.'], 403);
//        }

        $upload->delete();

//        return response()->json(["response" => true], 204);
        $response = [
            'data' => [
                'success' => true,
                'message' => 'Successfully Deleted'
            ]
        ];

        return Response::json($response);
    }

    public function encode($id)
    {
        $document = Upload::find($id);

        $media = new Media($document->raw_link, $document->disk);

        ThumbnailDocument::dispatch($media);
        WebMDocument::dispatch($media);
        HSLDocument::dispatch($media);

        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            $cmd = 'C:\xampp\php\php.exe C:\xampp\htdocs\nts-programs\nts-video-api\artisan queue:work --tries=5 --stop-when-empty';
        } else {
            $cmd = 'C:\php7\php.exe C:\Apache24\nts-video-api\artisan queue:work --tries=5 --stop-when-empty';
        }


        pclose(popen("start /B " . $cmd, "r"));

        $response = [
            'data' => [
                'success' => true
            ]
        ];

        return Response::json($response);
    }
}
