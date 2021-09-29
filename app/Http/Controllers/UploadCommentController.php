<?php

namespace App\Http\Controllers;

use App\Http\Resources\UploadCommentResource;
use App\UploadComment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;

class UploadCommentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'upload_id' => 'required',
                'content' => 'required'
            ]);

        if ($validator->fails()) {
            return response()->json(['state' => false, 'message' => $validator->errors()], 401);
        }


        $item = UploadComment::updateOrCreate(
            ['upload_id' => $request->upload_id,],
            [
                'upload_id' => $request->input('upload_id'),
                'content' => $request->input('content'),
            ]
        );


        return new UploadCommentResource($item);

    }

    /**
     * Display the specified resource
     *
     * @param \App\UploadComment $uploadComment
     * @return \Illuminate\Http\Response
     */
    public function show($upload_id)
    {
        $item = UploadComment::where('upload_id', $upload_id)->get();
        return new UploadCommentResource($item);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\UploadComment $uploadComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $uploadComment = UploadComment::where('upload_id', $id);

        $uploadComment->update($request->only(['content']));

        return new UploadCommentResource($uploadComment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\UploadComment $uploadComment
     * @return \Illuminate\Http\Response
     */
    public function destroy(UploadComment $uploadComment)
    {
        //
    }
}
