<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProGetAllSubClassName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_acc_sub_class`(IN `grp_id` INT(11), IN `comp_id` INT(11), IN `user_id` INT(11))
            SELECT mahsc.*,
mac.head_class_name,
mash.sub_head,
CONCAT(mah.head_name_type,'(',mah.account_code,')') as account_head_details

FROM mxp_acc_head_sub_classes mahsc
inner join mxp_acc_classes mac on(mac.mxp_acc_classes_id = mahsc.mxp_acc_classes_id)
inner join mxp_accounts_heads mah on(mah.accounts_heads_id = mahsc.accounts_heads_id)
inner join mxp_accounts_sub_heads mash on(mash.accounts_sub_heads_id = mahsc.accounts_sub_heads_id)

WHERE mahsc.group_id=grp_id
AND mahsc.company_id=comp_id
AND mahsc.user_id=user_id 
AND mahsc.is_deleted=0;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_acc_sub_class");
    }
}

