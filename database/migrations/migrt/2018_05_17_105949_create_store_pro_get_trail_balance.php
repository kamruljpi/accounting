<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProGetTrailBalance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_trial_balances`(IN `fromDate` VARCHAR(20), IN `toDate` VARCHAR(20))
SELECT ac.acc_final_name,
jp.account_head_id as chart_of_acc_id,
sub.head_sub_class_name, sub.mxp_acc_head_sub_classes_id,
cls.head_class_name, cls.mxp_acc_classes_id,
sd.sub_head, sd.accounts_sub_heads_id,
ad.head_name_type, ad.accounts_heads_id,
sum(jp.transaction_amount_debit-jp.transaction_amount_credit) as balance

FROM mxp_journal_posting jp
JOIN mxp_chart_of_acc_heads ac ON(jp.account_head_id=ac.chart_o_acc_head_id)
JOIN mxp_acc_head_sub_classes sub ON(sub.mxp_acc_head_sub_classes_id=ac.mxp_acc_head_sub_classes_id)
JOIN mxp_acc_classes cls ON(cls.mxp_acc_classes_id=ac.mxp_acc_classes_id)
JOIN mxp_accounts_sub_heads sd ON(sd.accounts_sub_heads_id=ac.accounts_sub_heads_id)
JOIN mxp_accounts_heads ad ON(ad.accounts_heads_id=ac.accounts_heads_id)
where jp.journal_date >= fromDate and jp.journal_date <= toDate
group by jp.account_head_id
order by ad.accounts_heads_id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_trial_balances");
    }
}