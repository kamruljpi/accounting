<?php

namespace App\Http\Controllers\dataget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserCompanyDetails extends Controller
{
	public $group_id;
    public $company_id;
    public $user_id;

    public function __construct() 
    {
    	$this->group_id 	= 	session()->get('group_id');
		$this->company_id 	= 	session()->get('company_id')|0;
		$this->user_id 		= 	session()->get('user_id');
	}
}
