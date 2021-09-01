<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BlockageLocationSpatial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // craete blockages table
        Schema::create('blockage_location_spatial',function(Blueprint $table){
            $table -> char('blk_location_id');
            $table -> point('blk_start_location');
            $table -> point('blk_end_location');
            $table -> text('blk_village');
            $table -> text('blk_tumbol');
            $table -> text('blk_district');
            $table -> text('blk_province');
            $table -> text('created_at');
            $table -> text('updated_at');
            $table -> point('blk_start_utm');
            $table -> point('blk_end_utm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // ลบตารางถ้าซ้ำ 
        Schema::drop('blockage_location_spatial');
    }
}
