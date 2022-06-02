<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER add_point AFTER INSERT ON `points_trx` FOR EACH ROW
                        BEGIN
                            SET @CEK = (SELECT COUNT(*) FROM `user_point` WHERE `user_id` = NEW.user_id AND `point_type_id` = NEW.point_type_id);
                            IF @CEK > 1 THEN
                                UPDATE `user_point` SET amount = amount + NEW.amount;
                            ELSE
                                INSERT INTO `user_point` (`user_id`, `point_type_id`, `amount`) VALUES (NEW.user_id, NEW.point_type_id, NEW.amount);
                            END IF;
                        END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `add_point`');
    }
}
