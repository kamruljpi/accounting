<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpJournalPosting extends Model {
	protected $table = 'mxp_journal_posting';
	protected $primaryKey = 'journal_posting_id';

	protected $fillable = ['transaction_type_id', 'account_head_id', 'sales_man_id', 'particular', 'description', 'transaction_amount_debit', 'transaction_amount_credit', 'reference_id', 'reference_no', 'cf_code', 'posting_group_id', 'company_id', 'group_id', 'is_deleted', 'is_active','client_id','product_id','bank_id','branch_id','journal_date', 'ledger_client_id'];
}
