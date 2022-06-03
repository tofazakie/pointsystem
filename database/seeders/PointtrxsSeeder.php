<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PointTrxs;

class PointtrxsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('point_trxs')->delete();
        $trxs = [
            ['user_id' => '1', 'point_type_id' => '1', 'amount'=> 3, 'description' => 'order #123'],
            ['user_id' => '1', 'point_type_id' => '2', 'amount'=> 1, 'description' => 'order #175'],
            ['user_id' => '2', 'point_type_id' => '1', 'amount'=> 2, 'description' => 'order #177'],
            ['user_id' => '1', 'point_type_id' => '1', 'amount'=> 5, 'description' => 'order #189'],
            ['user_id' => '3', 'point_type_id' => '1', 'amount'=> 6, 'description' => 'order #223'],
            ['user_id' => '1', 'point_type_id' => '1', 'amount'=> 3, 'description' => 'order #243'],
            ['user_id' => '3', 'point_type_id' => '2', 'amount'=> 8, 'description' => 'order #253'],
            ['user_id' => '2', 'point_type_id' => '2', 'amount'=> 1, 'description' => 'order #255'],
            ['user_id' => '2', 'point_type_id' => '1', 'amount'=> -5, 'description' => 'expired'],
            ['user_id' => '1', 'point_type_id' => '1', 'amount'=> -10, 'description' => 'expired'],
        ];

        foreach($trxs as $trx){
            PointTrxs::create($trx);
        }
    }
}
