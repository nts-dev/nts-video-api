<?php

namespace App\Http\Controllers;

use App\Http\Resources\ModuleResource;
use App\Module;
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
            ->orderBy('upload_count', 'asc')
            ->join('uploads', 'uploads.module_id', '=', 'modules.id')
            ->select(DB::raw('count(*) as upload_count, modules.*'))
            ->groupBy('modules.id')
            ->paginate(10));


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
        $item = Module::create([
            'user_id' => $request->user()->id,
            'subject_id' => $request->subject_id,
            'institution_id' => $request->institution_id,
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

        if ($request->user()->id !== $module->user_id) {
            return response()->json(['error' => 'You can only edit your own uploads.'], 403);
        }


        if ($module->update($request->only(['subject_id', 'institution_id', 'title', 'description']))) {
            return $response = response()->json(['message' => 'Success'], 200);
        } else
            return $response = response()->json(['error' => 'Error occurred'], 401);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
