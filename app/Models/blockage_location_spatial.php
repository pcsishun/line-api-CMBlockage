<?php

namespace App\Models;

use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

class blockage_location_spatial extends Model
{
    use SpatialTrait;

    protected $fillable = [
        'blk_location_id',
        'blk_village',
        'blk_tumbol',
        'blk_district',
        'blk_province',
        'created_at',
        'updated_at'
    ];

    protected $spatialFields = [
        'blk_start_location',
        'blk_end_location',
        'blk_start_utm',
        'blk_end_utm'
    ];
}
