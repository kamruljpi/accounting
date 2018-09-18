<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProcedureGetAllAccClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_acc_class`(IN `grp_id` INT(11), IN `comp_id` INT(11), IN `user_id` INT(11))
            SELECT mac.*,
            mash.sub_head,
            CONCAT(mah.head_name_type,'(',mah.account_code,')') as account_head_details

            FROM mxp_acc_classes mac
            inner join mxp_accounts_heads mah on(mac.accounts_heads_id = mah.accounts_heads_id)
            inner join mxp_accounts_sub_heads mash on(mash.accounts_sub_heads_id = mac.accounts_sub_heads_id)

            WHERE mac.group_id=grp_id
            AND mac.company_id=comp_id
            AND mac.user_id=user_id 
            AND mac.is_deleted=0;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_acc_class");
    }
}



