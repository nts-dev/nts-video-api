<?php

namespace App\Http\Controllers;

use App\Http\Resources\ModuleResource;
use App\Module;
use App\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
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


        return ModuleResource::collection(Module::orderBy('id', 'desc')
            ->get());


    }

    public function modulesInSubject($id)
    {
//        return ModuleResource::collection()->where('subject_id', '=', $id)->get());

        return ModuleResource::collection(Module::orderBy('id', 'desc')
            ->where('subject_id', '=', $id)
            ->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $item = Module::create([
            'user_id' => 1,
            'subject_id' => $request->subject_id,
            'institution_id' => 1,
            'title' => $request->title,
            'description' => $request->description
        ]);


        return new ModuleResource($item);
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
        return new ModuleResource(Module::find($id));
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
        $module = Module::find($id);

        if($module == null)
            return $response = response()->json([
                'message' => 'Error occurred',
                'data' => null], 401);


        // if ($request->user()->id !== $module->user_id) {
        //     return response()->json(['error' => 'You can only edit your own uploads.'], 403);
        // }


        if ($module->update($request->only(
            ['title', 'description']))) {
            return $response = response()->json(
                [
                    'message' => 'Success',
                    new ModuleResource($module)
                ], 200);
        } else
            return $response = response()->json([
                'message' => 'Error occurred',
                new ModuleResource($module)], 401);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $module = Module::find($id);

//        if ($request->user()->id !== $module->user_id) {
//            return response()->json(['error' => 'You are not allowed to remove this.'], 403);
//        }

        $module->delete();

        return response()->json(null, 204);

    }
}
