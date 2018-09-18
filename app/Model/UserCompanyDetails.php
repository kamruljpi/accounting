<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class UserCompanyDetails extends Model
{
	public $group_id;
    public $company_id;
    public $user_id;

    public function __construct() 
    {	
  //   	$req = new Request();
  //   	$this->group_id 	= 	$req->session()->get('group_id');
		// $this->company_id 	= 	$req->session()->get('company_id')|0;
		// $this->user_id 		= 	$req->session()->get('user_id');
	}

	public function getUserId()
	{
		return session()->get('user_id');
	}
}
