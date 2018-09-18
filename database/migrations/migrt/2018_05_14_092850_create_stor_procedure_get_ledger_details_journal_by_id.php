<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorProcedureGetLedgerDetailsJournalById extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `get_ledgers_by_acc_head_id`(IN `acc_head_id` INT(11), IN `fromDate` VARCHAR(20), IN `toDate` VARCHAR(20))
SELECT x.journal_posting_id,
x.journal_date,
x.particular,
x.transaction_amount_debit as debit, 
x.transaction_amount_credit as credit, 
SUM(y.bal) openingbalance
    
FROM
 ( 
   SELECT *,transaction_amount_debit-transaction_amount_credit as bal FROM mxp_journal_posting  WHERE account_head_id=acc_head_id  AND journal_date >= fromDate and journal_date <= toDate
 ) x

JOIN
 ( 
   SELECT *,transaction_amount_debit-transaction_amount_credit as bal FROM mxp_journal_posting  WHERE account_head_id=acc_head_id 
 ) y
     
   ON y.journal_posting_id <= x.journal_posting_id
    
GROUP BY x.journal_posting_id
ORDER BY x.journal_posting_id ASC;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP procedure IF EXISTS get_ledgers_by_acc_head_id");
    }
}
