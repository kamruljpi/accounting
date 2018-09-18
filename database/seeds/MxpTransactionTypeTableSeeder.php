<?php

use Illuminate\Database\Seeder;

class MxpTransactionTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mxp_transaction_type')->insert([
            'transaction_type_name' => 	'JOURNAL',
            'company_id' 			=> 	0,
            'group_id' 				=> 	1,
            'user_id' 				=> 	1,
            'is_deleted' 			=> 	0,
            'is_active' 			=> 	'1',
        ]);
        DB::table('mxp_transaction_type')->insert([
            'transaction_type_name' => 	'CASHDEPOSIT',
            'company_id' 			=> 	0,
            'group_id' 				=> 	1,
            'user_id' 				=> 	1,
            'is_deleted' 			=> 	0,
            'is_active' 			=> 	1,
        ]);
        DB::table('mxp_transaction_type')->insert([
            'transaction_type_name' => 	'SALESORDER',
            'company_id' 			=> 	0,
            'group_id' 				=> 	1,
            'user_id' 				=> 	1,
            'is_deleted' 			=> 	0,
            'is_active' 			=> 	1,
        ]);
        DB::table('mxp_transaction_type')->insert([
            'transaction_type_name' => 	'PURCHASEORDER',
            'company_id' 			=> 	0,
            'group_id' 				=> 	1,
            'user_id' 				=> 	1,
            'is_deleted' 			=> 	0,
            'is_active' 			=> 	1,
        ]);
        DB::table('mxp_transaction_type')->insert([
            'transaction_type_name' => 	'BANKDEPOSIT',
            'company_id' 			=> 	0,
            'group_id' 				=> 	1,
            'user_id' 				=> 	1,
            'is_deleted' 			=> 	0,
            'is_active' 			=> 	1,
        ]);
        DB::table('mxp_transaction_type')->insert([
            'transaction_type_name' => 	'BANKPAYMENT',
            'company_id' 			=> 	0,
            'group_id' 				=> 	1,
            'user_id' 				=> 	1,
            'is_deleted' 			=> 	0,
            'is_active' 			=> 	1,
        ]);
    }
}
