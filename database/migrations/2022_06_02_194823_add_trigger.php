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
        DB::unprepared('CREATE TRIGGER add_point AFTER INSERT ON `point_trxs` FOR EACH ROW
                        BEGIN
                            SET @VAL = (SELECT amount FROM `user_points` WHERE `user_id` = NEW.user_id AND `point_type_id` = NEW.point_type_id);
                            IF @VAL IS NOT NULL THEN
                                SET @NEW_VAL = @VAL + NEW.amount;
                                IF @NEW_VAL < 0 THEN SET @NEW_VAL = 0; END IF;
                                UPDATE `user_points` SET amount = @NEW_VAL WHERE `user_id` = NEW.user_id AND `point_type_id` = NEW.point_type_id;
                            ELSE
                                INSERT INTO `user_points` (`user_id`, `point_type_id`, `amount`) VALUES (NEW.user_id, NEW.point_type_id, NEW.amount);
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
