<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Grimzy\LaravelMysqlSpatial\Types\LineString;

class BlockagesController extends Controller
{
    // ความถี่การเกิดน้ำท่วม
    // function damage_freq($feq){
    //     return DB::table('blockages')
    //     ->join('blockage_crossections','blockages.blk_id','=','blockage_crossections.blk_id')
    //     ->where('blockages.damage_frequency','like','%'.$feq.'%')
    //     ->limit('10')
    //     ->get(columns:['damage_frequency','blockages.blk_id','blockages.blk_length','blockage_crossections.past']);
    // }

    function solution_project($blk_id){
        $data = DB::table('blockages')
                ->join('problem_details', 'blockages.blk_id', '=', 'problem_details.blk_id')
                ->join('experts', 'experts.blk_id', '=', 'blockages.blk_id')
                ->join('solutions', 'blockages.proj_id', '=', 'solutions.proj_id')
                ->where('blockages.blk_id', '=', $blk_id)
                ->limit('1')
                ->get(columns:['blockages.blk_id','problem_details.prob_level', 'problem_details.nat_weed_detail','experts.exp_solreport','solutions.sol_how']);
                return  $data;
    }

    function find_location_blk($province, $ampol, $tumbol){
        $data = DB::table('blockages')
                ->join('blockage_locations', 'blockages.blk_location_id', '=', 'blockage_locations.blk_location_id')
                ->where('blockage_locations.blk_end_location', '=', $tumbol, 'and', 'blockage_locations.blk_village', '=', $ampol, 'and', 'blockage_locations.blk_tumbol', '=', $province)
                ->limit('10')
                ->get(columns:['blockages.blk_id', 'blockages.blk_location_id', 'blockages.damage_level', 'blockages.damage_frequency']);
                return  $data;
    }

    // test la long 
    // function location_long_la($longitude, $latitude){
    //     $longitude_cast = (float)$longitude;
    //     $latitude_cast = (float)$latitude;
    //     $data = DB::table('location_test')
    //         ->select('*', DB::raw("sqrt( ((longitude  - $longitude_cast) * (longitude  - $longitude_cast)) + ((latitude -  $latitude_cast) * (latitude - $latitude_cast)) ) as distance")) 
    //         ->orderBy('distance','asc') 
    //         ->limit(5)
    //         ->get();
    //         return $data;
    // }

    // ข้อมูลค่าของ IDF 
    function water_idf_value($longitude, $latitude){
        $longitude_cast = (float)$longitude;
        $latitude_cast = (float)$latitude;
        $data = DB::table('wateridf')
        ->select('*', DB::raw("sqrt( ((longitude  - $longitude_cast) * (longitude  - $longitude_cast)) + ((latitude -  $latitude_cast) * (latitude - $latitude_cast)) ) as distance")) 
        ->orderBy('distance','asc') 
        ->limit(1)
        ->get();

        return $data;
    }

    // ------------- user logs effect to line ------------- // 

    // update new user click management //
    function insert_selection($id_user, $type_msg, $text_msg, $timestamp){
        DB::table('reply_msg')
        ->insert([
            'id_user' => $id_user,
            'type_msg' => $type_msg,
            'text_msg' => $text_msg,
            'timestamp' => $timestamp
            ]);
    }

    // find user click management //
    // function count_log($id_user){
    //     $data = DB::table('reply_msg')
    //     ->select(DB::raw('count(*) as user_count'))
    //     ->where('id_user','=',$id_user)
    //     ->get();
    //     return $data;
    // }

    // update user click management //
    // function update_log($id_user, $text_msg){
    //     DB::table('reply_msg')
    //     ->where('id_user',$id_user)
    //     ->update(array('text_msg' => $text_msg));
    // }

    
    // find user selection menu //
    function menu_selection($id_user){
        $data = DB::table('reply_msg')
        ->where('id_user','=',$id_user)
        ->orderBy('timestamp','desc')
        ->limit(1)
        ->get(columns:['text_msg']);

        return $data;
    }

    // -------------  ------------- ------------- // 
    
    function solution_mockup($id_location){
        $data = DB::table('mockup_solution')
        ->where('id_location', '=', $id_location)
        ->get();
        return $data;
    }

    // สถานที่เเจ้งปัญหา //
    function report_promble($aumpol,$tumbol){
        $data = DB::table('problem_report')
        ->where('tumbol', '=', $tumbol)
        ->where('aumpol', '=', $aumpol)
        ->limit(1)
        ->get();
        return $data;
    }

    // Blockage location distance that you are near
    function location_test_2($longitude, $latitude){
        $longitude_cast = (float)$longitude;
        $latitude_cast = (float)$latitude;
        $data = DB::table('blockage_locations')
        ->select(DB::raw("
        blk_location_id, 
        CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location),'$.coordinates[0]') AS float) as latitude_start, 
        CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location), '$.coordinates[1]') AS float) as longitude_start, 
        concat(blk_location_id,' ', blk_village,' ',blk_tumbol,' ',blk_province) as location,
        sqrt( ((CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location), '$.coordinates[1]') AS float)  - $longitude_cast) * (CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location), '$.coordinates[1]') AS float)  - $longitude_cast)) + ((CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location),'$.coordinates[0]') AS float) -  $latitude_cast) * (CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location),'$.coordinates[0]') AS float) - $latitude_cast)) ) as distance",
        ))
        ->orderBy(DB::raw("sqrt( ((CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location), '$.coordinates[1]') AS float)  - $longitude_cast) * (CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location), '$.coordinates[1]') AS float)  - $longitude_cast)) + ((CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location),'$.coordinates[0]') AS float) -  $latitude_cast) * (CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location),'$.coordinates[0]') AS float) - $latitude_cast)) )"), 'asc')
        ->limit(5)
        ->get();
        // CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_end_location), '$.coordinates[0]') AS float) as latitude_end, 
        // CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_end_location), '$.coordinates[1]') AS float)  as longitude_end,
        return $data;
    }

    // // test log <> la //
    // function testing_long_la($longitude, $latitude){
    //     $longitude_cast = (float)$longitude;
    //     $latitude_cast = (float)$latitude;
    //     $data = DB::table('blockage_locations')
    //     ->select(DB::raw("
    //     blk_location_id, 
    //     CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location),'$.coordinates[0]') AS float) as latitude_start, 
    //     CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location), '$.coordinates[1]') AS float) as longitude_start, 
    //     concat(blk_location_id,' ', blk_village,' ',blk_tumbol,' ',blk_province) as location,
    //     sqrt( ((CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location), '$.coordinates[1]') AS float)  - $longitude_cast) * (CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location), '$.coordinates[1]') AS float)  - $longitude_cast)) + ((CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location),'$.coordinates[0]') AS float) -  $latitude_cast) * (CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location),'$.coordinates[0]') AS float) - $latitude_cast)) ) as distance",
    //     ))
    //     ->orderBy(DB::raw("sqrt( ((CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location), '$.coordinates[1]') AS float)  - $longitude_cast) * (CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location), '$.coordinates[1]') AS float)  - $longitude_cast)) + ((CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location),'$.coordinates[0]') AS float) -  $latitude_cast) * (CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_start_location),'$.coordinates[0]') AS float) - $latitude_cast)) )"), 'asc')
    //     ->limit(5)
    //     ->get();
    //     // CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_end_location), '$.coordinates[0]') AS float) as latitude_end, 
    //     // CAST(JSON_EXTRACT(ST_AsGeoJSON(blk_end_location), '$.coordinates[1]') AS float)  as longitude_end,
    //     dd($data);
        
    //     return $data;
    // }
}

 