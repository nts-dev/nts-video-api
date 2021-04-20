<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::apiResource('endpoint', 'EndPointTest');

Route::post('logout', 'AuthController@logout');

Route::post('register', 'AuthController@register');

Route::post('login', 'AuthController@login');

Route::post('user', 'AuthController@getUser');

Route::post('refresh', 'AuthController@refresh');

Route::get('guard', 'AuthController@login')->name('login');

Route::apiResource('videos', 'UploadController');

Route::get('/videos/subjects/{id}', 'UploadController@uploadsInSubject');

Route::get('/videos/category/{id}', 'UploadController@uploadsInCategory');

Route::post('/videos/upload', 'UploadFileController@store');

Route::post('/videos/split', 'UploadFileController@split');

Route::post('/videos/encode', 'UploadFileController@encodeExisting');



Route::apiResource('modules', 'ModuleController');

Route::apiResource('comments', 'UploadCommentController');

Route::apiResource('timelineinfo', 'UploadTimelineInformationController');

Route::get('/modules/subject/{id}', 'ModuleController@modulesInSubject');

Route::get('/modules/subject/{id}', 'ModuleController@modulesInSubject');

Route::apiResource('profiles', 'ProfileController');

Route::apiResource('subjects', 'SubjectController');

Route::apiResource('playbackstatistics', 'PlaybackStatisticsController');

Route::apiResource('userprofiles', 'UserProfileController');

Route::apiResource('issues', 'IssuesController');

Route::apiResource('institutions', 'InstitutionController');

Route::apiResource('dashboard', 'DashboardController');

Route::apiResource('saveditems', 'SavedItemsController');

Route::apiResource('useruploads', 'MyUploadsController');

Route::apiResource('authors', 'UserController');

//Route::apiResource('uploads', 'UploadController');

Route::post('/videos/{id}/viewed', 'ViewsController@store');



//Route::get('/userprofiles', function (){
//    return \App\Http\Resources\UserProfileResource::collection(User::all());
//});
//
//Route::get('/userprofiles/{id}', function ($id){
//    return \App\Http\Resources\UserProfileResource::collection(User::find($id));
//});

