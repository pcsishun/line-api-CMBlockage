<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BlockagesController;
 
// |--------------------------------------------------------------------------
// | Web Routes
// |--------------------------------------------------------------------------
// |
// | Here is where you can register web routes for your application. These
// | routes are loaded by the RouteServiceProvider within a group which
// | contains the "web" middleware group. Now create something great!
// |
// */

Route::get('solution_project/{blk_id}',[BlockagesController::class, 'solution_project']);
Route::get('water_idf/{longitude}/{latitude}', [BlockagesController::class, 'water_idf_value']);

// api management menu // 
Route::get('api_selection/{id_user}/{type_msg}/{text_msg}',[BlockagesController::class, 'insert_selection']);
Route::get('menu_selection/{id_user}',[BlockagesController::class, 'menu_selection']);
Route::get('counting_user/{id_user}',[BlockagesController::class, 'count_log']);
Route::get('update_log_user/{id_user}/{text_msg}',[BlockagesController::class, 'update_log']);
// ------------------- // 

// สถานที่เเจ้งปัญหา
Route::get('problem_report/{tumbol}',[BlockagesController::class, 'report_promble']);

// real location blockage 
Route::get('location_test2/{longitude}/{latitude}',[BlockagesController::class, 'location_test_2']);


// don't have in menu lineChat bot //
// manual input province && ampol && tumbol (in menu we don't have)
Route::get('find_location_blk/{province}/{ampol}/{tumbol}',[BlockagesController::class, 'find_location_blk']);
// ความถี่การเกิดน้ำท่วม
// Route::get('damage_freq/{feq}', [BlockagesController::class,'damage_freq']);
// เเนวทางการเเก้ไขปัญหา 
Route::get('solution_mockup/{id_location}',[BlockagesController::class, 'solution_mockup']);
// ------------------------------- //


// test long la // 
// Route::get('testing_long_la/{longitude}/{latitude}',[BlockagesController::class, 'testing_long_la']);
// Route::get('find_distance/{longitude}/{latitude}', [BlockagesController::class, 'location_long_la']);
// ---------------------- //