<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use App\Upload;


class UploadFileController extends Controller
{
    //

    public function store(Request $request)
    {
 
       $validator = Validator::make($request->all(), 
              [ 
              'user_id' => 'required',
              'file' => 'required|mimes:mp3,mp4|max:20048',
             ]);   
 
    if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 401);                        
         }  


         $row_file = $request->file('file');
 
  
        if ($files = $row_file) {

            $subject = $request->subject_id;
            $category = $request->content_id;

            $PATH = 'public/media/'.$subject."/".$category;
            // $mimeType = File::get($row_file)->getMimeType();

             
            //store file into document folder
            $file = $request->file->store($PATH);
 
            //store your file into database
            // $document = Upload::find($request->id);
            // $document->user_id = $request->user_id;
            // $document->save();
              
            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $file,
                // "type" => $mimeType,
            ]);
  
        }
 
  
    }
}
