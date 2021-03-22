<?php

namespace App\Http\Controllers;

use App\Http\Commons\Util;
use App\Http\Documents\HSLDocument;
use App\Http\Documents\MediaDocument;
use App\Http\Documents\ThumbnailDocument;
use App\Http\Documents\WebMDocument;
use App\Http\Documents\model\Media;
use App\Http\Documents\SplittedDocument;
use App\Jobs\ConvertToHSLDocument;
use App\Jobs\ConvertToWebMDocumnet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Config;
use League\Flysystem\FileNotFoundException;
use Validator, Redirect, Response, File;
use App\Upload;


class UploadFileController extends Controller
{
    //

    private function validateInput(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'project_id' => 'required',
                'id' => 'required',
                'content_id' => 'required',
                'file' => 'required|mimes:mp3,mp4,mkv',
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        return true;
    }

    /**
     *
     *
     *
     *
     *
     *
     *
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     *
     *
     *
     */


    public function split(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'start' => 'required',
                'end' => 'required',
                'asInDisk' => 'required',
                'disk' => 'required'
            ]);

        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 401);


        if (!Storage::disk()->exists($request->asInDisk))
            return response()->json(['error' => 'Parent file requested does not exist'], 401);


        exit();

//        $this->validateInput($request);


        $row_file = $request->file('file');


        if ($files = $row_file) {
            $subject = $request->subject_id;
            $category = $request->content_id;

            $PRIMARY_PATH = 'media/' . $subject . "/" . $category;
            $FILE_PATH = 'public/' . $PRIMARY_PATH;

            //store file into document folder
            $file = $request->file->store($FILE_PATH);
            $file_abs = substr($file, 7); //remove 'public' from the path


            $media = new Media($file_abs, $PRIMARY_PATH);

            $splitedDocument = new SplittedDocument($media, $request->start, $request->end);

//            store your file into database
            $document = Upload::find($request->id);
            $document->disk = $FILE_PATH;
            $document->raw_link = $file;
            $document->time_encoded = now();
            $document->save();

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $file,
                // "type" => $mimeType,
            ]);

        }

    }


    /**
     *
     *
     *
     *
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     */

    public function encodeExisting(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'project_id' => 'required',
                'id' => 'required',
                'module_id' => 'required',
            ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }


        $DOCID = $request->id;

        $document = Upload::find($DOCID);


        if ($document == null)
            return response()->json(['error' => 'This record does not exist'], 401);

        $PROJECT = Util::generateProjectId($request->project_id);

        $CATEGORY = $request->content_id;

        $PRIMARYPATH = $PROJECT . "/" . $CATEGORY;

        $OLDVIDEODIRECTORY = $PRIMARYPATH . "/video/" . $DOCID;

        $OLDAUDIODIRECTORY = $PRIMARYPATH . "/audio/" . $DOCID;

        $VIDEOPATH = $OLDVIDEODIRECTORY . "/media.mp4";

        $AUDIOPATH = $OLDAUDIODIRECTORY . "/media.mp3";

        $videoExists = Storage::disk(MediaDocument::DISK)->exists($VIDEOPATH);

        $audioExists = Storage::disk(MediaDocument::DISK)->exists($AUDIOPATH);


        $media = "";
        $seed = "gjsYTBbUui" . $DOCID;
        $GENERATEDFILENAME = hash('sha256', $seed);

        $NEWDIRPATH = $PRIMARYPATH . "/" . $DOCID;

        $NEWFILEPATH = "";
        if ($videoExists) {
            $NEWFILEPATH = $NEWDIRPATH . "/" . $GENERATEDFILENAME . ".mp4";
            $media = new Media($VIDEOPATH, $NEWFILEPATH);

            Storage::disk(MediaDocument::DISK)->move($VIDEOPATH, $NEWDIRPATH);

        } else if ($audioExists) {
            $NEWFILEPATH = $NEWDIRPATH . "/" . $GENERATEDFILENAME . ".mp4";
            $media = new Media($AUDIOPATH, $NEWFILEPATH);
        }

        assert($media != null);


        HSLDocument::dispatch($media);
        WebMDocument::dispatch($media);
        ThumbnailDocument::dispatch($media);

        $document->disk = $NEWDIRPATH;
        $document->raw_link = $NEWFILEPATH;
        $document->time_encoded = now();
        $document->save();

        return response()->json([
            "success" => true,
            "message" => "File successfully uploaded",
            "file" => Storage::disk('media')->get($NEWFILEPATH),
            // "type" => $mimeType,
        ]);


    }

    /**
     *
     *
     *
     *
     *
     * @param Request $request
     * @return false|\Illuminate\Http\JsonResponse|string
     *
     *
     *
     */

    public function store(Request $request)
    {

        $this->validateInput($request);

        $document = Upload::find($request->id);


        if ($document == null)
            return response()->json(['error' => 'This record does not exist'], 401);

//
//        try {
//            $stream = Storage::disk('media')->getDriver()
//                ->readStream('P010424\239\video\312\media.mp4');
//        } catch (FileNotFoundException $e) {
//        }
//
//
//        try {
//            return Storage::disk('media')->getDriver()->getMimetype('P010424\239\video\312\media.mp4');
//        } catch (FileNotFoundException $e) {
//        }

//        response('test.jpg');

        $row_file = $request->file('file');


        if ($files = $row_file) {

            $SUBJECT = Util::generateProjectId($request->subject_id);
            $CATEGORY = $request->content_id;

            $PRIMARY_PATH = $SUBJECT . "/" . $CATEGORY;


            $FILE_PATH = MediaDocument::DISK . '/' . $PRIMARY_PATH;

            //store file into document folder
            $file = $request->file->store($FILE_PATH);
            $file_abs = substr($file, 7); //remove 'public' from the path


            $media = new Media($file_abs, $PRIMARY_PATH);


            $webMDocument = new WebMDocument($media);
            $hslDocument = new HSLDocument($media);
            $thumbnailDocument = new ThumbnailDocument($media);

            $webMDocument->handle();
            $hslDocument->handle();

            /**
             *
             *      *Document are ShouldQueue jobs
             *  However, the implementation throws exceptions in unix
             *      -   ffmpeg save directory permission denied
             *      -   ffmpeg hsl .ts file not found in directory
             *
             *
             *  These exceptions are not thrown, however, when handle is called directly. For this reason, call the commented block when on UNIX
             *
             *  Windows works flawlessly
             */

//            HSLDocument::dispatch($media);
//            WebMDocument::dispatch($media);
//            ThumbnailDocument::dispatch($media);


//            store your file into database

            $document->disk = $FILE_PATH;
            $document->raw_link = $file;
            $document->time_encoded = now();
            $document->save();

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $file,
                // "type" => $mimeType,
            ]);

        }

    }


}
