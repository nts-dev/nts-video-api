<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EndPointTest extends Controller
{
    public function index()
    {
        //
        return  response()->json(['message'=>'OK'], 201);
    }
}
