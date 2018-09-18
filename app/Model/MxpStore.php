<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MxpStore extends Model
{
    protected $table = "mxp_store";
    protected $fillable = ['name', 'location', 'company_id', 'com_group_id', 'user_id','status', 'is_deleted', 'status'];
}
