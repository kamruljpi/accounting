<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 900px;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-2 pull-right">
                    <form action="{{ Route('product_purchase_list_view') }}" method="get">
                        <input type="submit" name="close_lc_purchase_update_modal" id="close_lc_purchase_update_modal" value="Cancel" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                        
                    </form>
                </div>
                <h3 class="modal-title">
                    Update Local Purchase Product
                </h3>
            </div>
            <div class="modal-body" id="modal">
                <div class="row">
                    <form action="{{ Route('pro_product_purchase_update_action') }}" class="form-horizontal" method="post" id="pro_purchase_update_form_data">

                        <div class="alert alert-danger" id="show_error" style="display: none; margin-left: 20px">
                        </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="prucahse_product_id" value="{{ $purchaseProduct->id }}">
                        <div class="purchase_table_input">
                            <div class="purchase_table_left col-sm-5">
                                <div class="col-md-12">
                                    <div class="col-md-6" id="select_client">
                                        <label>Client: </label>
                                        <div id="divForSelectingClient"></div>
                                        <select class="form-control" name="client" id="client" class="client">
                                            <option value="">Select Client</option>
                                            {{--<option value="-1">Create new Client</option>--}}
                                            @foreach($clients as $client)
                                                <option value="{{ $client->chart_o_acc_head_id }}" {{ ($purchaseProduct->client_id == $client->chart_o_acc_head_id)? "selected":"" }}> {{ $client->acc_final_name }} </option>
                                            @endforeach
                                        </select>
                                            <div class="UrgentClientView"></div>
                                    </div>
                 
                                  
                                    <div class="col-md-6">
                                        <label>Date: </label>
                                        <input class="form-control" type="date" name="date" id="date" value="{{ $purchaseProduct->purchase_date}}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label>Product Group: </label>
                                    <select class="form-control" name="product_group" id="product_group">
                                        <option value="">Select Group</option>
                                        @foreach($productGroup as $group)
                                            <option value="{{ $group->id }}"  {{ ($purchaseProduct->product->product_group_id == $group->id)? "selected":"" }}> {{ $group->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label>Product: </label>
                                    <select class="form-control" name="product" id="product">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"  {{ ($purchaseProduct->product_id == $product->id)? "selected":"" }}> {{ $product->pro_name.'('.$product->pac_name.'('.$product->quantity.')'.$product->unit_name.'('.$product->unit_quantity.')' }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label>Chart of Account: </label>

                                    <select class="form-control" name="chart_of_acc" id="chart_of_acc" required>
                                    <option value="">Select Account</option>
                                    @foreach($chart_of_accounts as $chart_of_account)
                                        <option value="{{ $chart_of_account->chart_o_acc_head_id }}" {{ ($purchaseProduct->account_head_id == $chart_of_account->chart_o_acc_head_id)? "selected":"" }}> {{ $chart_of_account->acc_final_name }} </option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="purchase_table_left col-sm-7">
                                    <div class="col-md-12">
                                    <label>Invoice No: </label>
                                    <input class="form-control" type="text" name="invoice" id="invoice" value="{{ $purchaseProduct->invoice->invoice_code}}">
                                </div>

                                <div class="col-md-12 vat_tax_row">
                                    <div class="col-md-12">
                                        <label>Vat Tax</label>
                                    </div>
                                    
                                    @foreach($vattaxes as $vattax)
                                    <td>
                                        <div class="col-md-{{ round(12/(count($vattaxes))) }}">
                                            <input class="form-control" name="vattax_percentage[]" type="text" value="{{ $vattax->percent}}">
                                            <input name="vattax_id[]" type="hidden" value="{{ $vattax->vat_tax_id }}">
                                            <input name="vattaxcal_id[]" type="hidden" value="{{ $vattax->id }}">
                                        </div>
                                    </td>
                                    @endforeach
                                </div>

                                <div class="col-md-12   purchase_table_right_bottom">
                                   <div class="col-md-3">
                                        <label>Quantity</label>
                                        <input class="form-control" type="text" name="quantity" value="{{ $purchaseProduct->quantity}}">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Price/Unit</label>
                                        <input class="form-control" type="text" name="price" value="{{ $purchaseProduct->price}}">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Total Price</label>
                                        <input class="form-control" type="text" name="total_price" id="total_price" readonly value="{{ $purchaseProduct->quantity*$purchaseProduct->price }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Total with vat/tax</label>
                                        <input class="form-control" type="text" name="total_price_with_vat_tax" id="total_price_with_vat_tax" readonly value="{{ isset($vattaxes[0])? $vattaxes[0]->total_amount_with_vat:0 }}">
                                    </div>
                                </div>

                                <div class="col-md-12 row paid_due_right_bottom">
                                   <div class="col-md-4">
                                        <label>Pay Amount</label>
                                        <input class="form-control" type="text" name="pay_amount" id="pay_amount">
                                    </div>
                                   <div class="col-md-4">
                                        <label>Paid Amount</label>
                                        <input class="form-control" type="text" name="paid_amount" value="{{ isset($vattaxes[0])? $vattaxes[0]->total_amount_with_vat-$purchaseProduct->due_amount:0}}" readonly id="paid_amount">
                                    </div>
                                    <input type="hidden" name="root_paid_amount" value="{{ isset($vattaxes[0])? $vattaxes[0]->total_amount_with_vat-$purchaseProduct->due_amount:0}}" id="root_paid_amount">
                                    <div class="col-md-4">
                                        <label>Amount to pay</label>
                                        <input class="form-control" type="text" name="due_amount" id="due_amount" readonly value="{{ $purchaseProduct->due_amount}}" id="due_amount">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label>Bonus</label>
                                    <input class="form-control" type="text" name="bonus" value="{{ $purchaseProduct->bonus}}">
                                </div>
                                <div class="col-md-3 col-md-offset-5">
                                    <br>
                                    <input type="submit" name="purchase_data_update" id="purchase_data_update" value="Update" class="btn btn-success col-md-12 purchase_data_update">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset("js/custom.js") }}"></script>
<script type="text/javascript">
    $('#myModal').modal({backdrop: 'static', keyboard: false})
</script>
