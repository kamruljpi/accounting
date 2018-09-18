<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpPacket extends Model
{
    protected $table = "mxp_packet";
    protected $fillable = ['name', 'quantity', 'unit_quantity', 'unit_id', 'company_id', 'com_group_id', 'user_id', 'is_active', 'is_deleted'];
}
