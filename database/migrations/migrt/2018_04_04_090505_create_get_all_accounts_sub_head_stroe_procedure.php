<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetAllAccountsSubHeadStroeProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_accounts_sub_head`(IN `grp_id` INT(11), IN `comp_id` INT(11), IN `user_id` INT(11))
            SELECT mash.*,
            CONCAT(mah.head_name_type,'(',mah.account_code,')') as account_head_details

            FROM mxp_accounts_sub_heads mash
            inner join mxp_accounts_heads mah on(mash.accounts_heads_id = mah.accounts_heads_id)

            WHERE mash.group_id=grp_id
            AND mash.company_id=comp_id
            AND mash.user_id=user_id 
            AND mash.is_deleted=0;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_accounts_sub_head");
    }
}
