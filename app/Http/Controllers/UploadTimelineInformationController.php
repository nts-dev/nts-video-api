<?php

namespace App\Http\Controllers;

use App\Http\Resources\UploadTimelineInformationResource;
use App\UploadTimelineInformation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;

class UploadTimelineInformationController extends Controller
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


        $item = UploadTimelineInformation::updateOrCreate(
            ['upload_id' => $request->upload_id,],
            [
                'upload_id' => $request->upload_id,
                'content' => $request->content,
            ]
        );


        return new UploadTimelineInformationResource($item);
    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = UploadTimelineInformation::where('upload_id', $id)->get();
        return new UploadTimelineInformationResource($item);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\UploadTimelineInformation $uploadTimelineInformation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $uploadTimelineInformation = UploadTimelineInformation::where('upload_id', $id);

        $uploadTimelineInformation->update($request->only(['content']));
        return new UploadTimelineInformationResource($uploadTimelineInformation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\UploadTimelineInformation $uploadTimelineInformation
     * @return \Illuminate\Http\Response
     */
    public function destroy(UploadTimelineInformation $uploadTimelineInformation)
    {
        //
    }
}
