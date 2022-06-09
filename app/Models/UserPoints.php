<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPoints extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'point_type_id',
        'value'
    ];

    /**
     * Get user points.
     *
     * @var array
     */
    public static function getUserPoint($user_id)
    {
        $points = self::where('user_id', $user_id)->get();

        return $points;
    }
}
