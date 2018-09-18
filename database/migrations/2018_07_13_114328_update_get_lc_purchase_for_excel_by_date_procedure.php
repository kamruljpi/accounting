<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGetLcPurchaseForExcelByDateProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP procedure IF EXISTS get_lc_purchase_for_excel_by_date");
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_lc_purchase_for_excel_by_date`(IN `fromdate` VARCHAR(20), IN `todate` VARCHAR(20))
SELECT mlp.lc_no as LcNo,mlp.purchase_date as OpenDate,
mlp.lc_amount_taka LCValue,mlp.lc_margin as Margin,
mlp.total_bank_charges as BankCharges,mlp.others_cost as CustomDutyOthersCost,
mlp.total_amount_with_other_cost as TotalCost,mlp.total_paid as PaidAmount,
mlp.due_payment as DuePayment,
GROUP_CONCAT(pr.name,'(',pk.name,'(',pk.quantity,') ',un.name,'(',pk.unit_quantity,'))') as ProductDetails,
mu.acc_final_name as ClientDetails

FROM mxp_lc_purchases mlp
inner join mxp_product pr on(mlp.product_id = pr.id)
inner JOIN mxp_packet pk ON(pr.packing_id=pk.id)
inner join mxp_unit un ON(un.id=pk.unit_id)
inner join mxp_chart_of_acc_heads mu on(mlp.client_id=mu.chart_o_acc_head_id)
inner join mxp_invoice mi on(mlp.invoice_id=mi.id)
WHERE mlp.purchase_date>=fromdate AND mlp.purchase_date<=todate
GROUP BY mlp.lc_purchase_id;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_lc_purchase_for_excel_by_date");
    }
}
