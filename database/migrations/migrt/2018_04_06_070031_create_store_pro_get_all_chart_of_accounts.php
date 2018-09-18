<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProGetAllChartOfAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_chart_of_accounts`(IN `grp_id` INT(11), IN `comp_id` INT(11), IN `user_id` INT(11))
            SELECT mcoah.*,
mahsc.head_sub_class_name,
mac.head_class_name,
mash.sub_head,
CONCAT(mah.head_name_type,'(',mah.account_code,')') as account_head_details

FROM mxp_chart_of_acc_heads mcoah
inner join mxp_acc_head_sub_classes mahsc on(mahsc.mxp_acc_head_sub_classes_id = mcoah.mxp_acc_head_sub_classes_id)
inner join mxp_acc_classes mac on(mac.mxp_acc_classes_id = mcoah.mxp_acc_classes_id)
inner join mxp_accounts_heads mah on(mah.accounts_heads_id = mcoah.accounts_heads_id)
inner join mxp_accounts_sub_heads mash on(mash.accounts_sub_heads_id = mcoah.accounts_sub_heads_id)

WHERE mcoah.group_id=grp_id
AND mcoah.company_id=comp_id
AND mcoah.user_id=user_id 
AND mcoah.is_deleted=0;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_all_chart_of_accounts");
    }
}

