<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpStock extends Model
{
    protected $table = "mxp_stock";
    
    protected $fillable = ['product_id', 'store_id', 'invoice_id', 'purchase_id', 'lot_id','avg_price_unit', 'company_id', 'com_group_id', 'user_id', 'quantity', 'available_quantity', 'available_quantity', 'status', 'is_deleted'];
}
