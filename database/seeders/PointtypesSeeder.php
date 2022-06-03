<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PointTypes;

class PointtypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('point_types')->delete();
        $types = [
            ['name' => 'drinks'], 
            ['name' => 'foods']
        ];

        foreach($types as $type){
            PointTypes::create($type);
        }
    }
}
