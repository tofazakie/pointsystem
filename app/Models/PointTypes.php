<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTypes extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get array of point types.
     *
     * @var array
     */
    public static function arrPointTypes()
    {
        $types = self::get();
        $arr = [];
        foreach($types as $type){
            $arr[$type->id] = $type->name;
        }

        return $arr;
    }
}
