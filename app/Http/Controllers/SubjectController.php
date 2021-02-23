<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubjectResource;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
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
        return SubjectResource::collection(Subject::orderBy('subject_title', 'asc')->paginate(200));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = Subject::create([
            'subject_description' => $request->subject_description,
            'subject_title' => $request->subject_title
        ]);

        return new SubjectResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
        return new SubjectResource($subject);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        //
//        if($request->user()->id !== $upload->user()->id){
//            return response()->json(['error' => 'You can only edit your own uploads.'], 403);
//        }

        $subject->update($request->only(['subject_title', 'subject_description']));

        return new SubjectResource($subject);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        //
        $subject->delete();
        return response()->json(null, 204);
    }
}
