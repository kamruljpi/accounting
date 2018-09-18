<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGetLedgerDetailsJournalByIdProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP procedure IF EXISTS get_ledger_details_journal_by_id");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_ledger_details_journal_by_id`(IN `journal_id` INT(11), IN `acc_head_id` INT(11))
if(acc_head_id = -1) then
SELECT mjp.*,
 mu.acc_final_name as client_details, 
 mjp.account_head_id as acc_final_name,
 CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as productDetails
 
FROM mxp_journal_posting mjp 
inner JOIN mxp_chart_of_acc_heads mu ON(mu.chart_o_acc_head_id=mjp.client_id) 
inner JOIN mxp_product pr ON(pr.id=mjp.product_id) 
inner JOIN mxp_packet pk ON(pk.id=pr.packing_id) 
inner JOIN mxp_unit un ON(un.id=pk.unit_id) 

WHERE mjp.journal_posting_id=journal_id;
else
SELECT mjp.*,
 mu.acc_final_name as client_details, 
 mcah.acc_final_name,CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as productDetails
 
FROM mxp_journal_posting mjp
inner JOIN mxp_chart_of_acc_heads mu ON(mu.chart_o_acc_head_id=mjp.client_id) 
inner JOIN mxp_chart_of_acc_heads mcah ON(mcah.chart_o_acc_head_id=mjp.account_head_id) 
inner JOIN mxp_product pr ON(pr.id=mjp.product_id) 
inner JOIN mxp_packet pk ON(pk.id=pr.packing_id) 
inner JOIN mxp_unit un ON(un.id=pk.unit_id) 

WHERE mjp.journal_posting_id=journal_id;
 end if;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_ledger_details_journal_by_id");
    }
}
