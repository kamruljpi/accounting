<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Model\MxpPacket;
use App\Http\Controllers\Message\StatusMessage;
use App\MxpUnit;

class PacketProduction extends Controller
{
    public function packetList(Request $request)
    {
    	$packetList = MxpPacket::where('com_group_id',$this->getGroupId())->
            where('company_id', $this->getConpanyId())->
            where('is_active',1)->
            where('is_deleted', 0)->get();

        $units = MxpUnit::where('com_group_id',$this->getGroupId())->
        where('company_id', $this->getConpanyId())->
        where('is_active',1)->
        where('is_deleted', 0)->get();
        return view('product.packeting.packet_list', ['packetList' => $packetList, 'units' => $units]);
    }

    public function addPacketForm(Request $request)
    {

        $units = MxpUnit::where('com_group_id',$this->getGroupId())->
        where('company_id', $this->getConpanyId())->
        where('is_active',1)->
        where('is_deleted', 0)->get();

        return view('product.packeting.add_packet', ['request' => $request, 'units' => $units]);
    }

    public function addPacket(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'quantity'      => 'required|integer',
            'unit_quantity' => 'required|integer',
            'unit_id'       => 'required',
            'company_id'    => 'required',
            'group_id'      => 'required',
            'user_id'       => 'required',
            'is_active'     => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
        }

        $addPacket = new MxpPacket();
        $addPacket->name = $request->name;
		$addPacket->quantity = $request->quantity;
		$addPacket->unit_quantity = $request->unit_quantity;
		$addPacket->unit_id = $request->unit_id;
		$addPacket->company_id = $request->company_id;
		$addPacket->com_group_id = $request->group_id;
		$addPacket->user_id = $request->user_id;
		$addPacket->is_active = $request->is_active;
		$addPacket->save();

		StatusMessage::create('new_packet_added', 'New Packet Added Successfully');

        return Redirect()->Route('packet_list_view');
    }



	public function updateForm(Request $request){

		$packet = MxpPacket::get()->where('id', '=', $request->pid)->first();
		$units = MxpUnit::where('com_group_id',$this->getGroupId())->
        where('company_id', $this->getConpanyId())->
        where('is_active',1)->
        where('is_deleted', 0)->get();

		return view('product.packeting.update_packet', ['packet' => $packet, 'request' => $request, 'units' => $units]);
	}

	public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'quantity'      => 'required|integer',
            'unit_quantity' => 'required|integer',
            'unit_id'       => 'required',
            'is_active'     => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator->messages());
        }

		$packet = MxpPacket::find($request->pid);
		
		$packet->name = $request->name;
		$packet->quantity = $request->quantity;
		$packet->unit_quantity = $request->unit_quantity;
		$packet->unit_id = $request->unit_id;
		$packet->is_active = $request->is_active;
		$packet->save();

		StatusMessage::create('new_packet_updated', 'New Packet updated Successfully');

		return redirect()->back();
	}

    public function delete(Request $request){
    	$packet = MxpPacket::find($request->pid);
        $packet->delete();

        StatusMessage::create('packet_deleted', 'Packet Deleted Successfully');

        return redirect()->back();
    }

}
