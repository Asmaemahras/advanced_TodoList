<?php
use App\Http\Resources\TodayTaskResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Upcoming;
use App\Models\Today;
use App\Http\Resources\UpcomingResource;
use Illuminate\Support\Facades\DB;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/upcoming", function(){
    $upcoming = Upcoming::all();

    return UpcomingResource::collection($upcoming);
});
// ajouter un nv task !

Route::post('/upcoming', function(Request $request) {
    return Upcoming::create([
        'title' => $request->title,
        'taskId' => $request->taskId,
        'waiting' => $request->waiting
    ]);
});

// supprimer upcoming task

Route::delete('/upcoming/{taskId}', function($taskId)
{
    DB::table('upcomings')->where('taskId', $taskId)->delete();

    return 204;
});

// task d'aujourd'hui
Route::get("/dailytask", function(){
    $todaytask = Today::all();
    return TodayTaskResource::collection($todaytask);
});

Route::post('/dailytask', function (Request $request) {
    return Today::create([
        'id' => $request->id,
        'title' => $request->title,
        'taskId' => $request->taskId
    ]);

});

//supprimer task d'aujourd'hui

Route::delete('/dailytask/{taskId}', function($taskId)
{
    DB::table('todays')->where('taskId', $taskId)->delete();

    return 204;
});