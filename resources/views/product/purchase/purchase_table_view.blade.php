            <div class="purchase_table col-md-12">
            <div class="view_form"></div>
                <form action="{{ Route('product_purchase_add') }}" method="post" role="form" id="form" >
                    <table class="table table-bordered purchase_table" id="tblSearch">
                        <thead>
                            <tr>
                                    <th class="hidden_th">
                                        {{ trans('others.mxp_menu_product_group') }}
                                    </th>
                                    <th class="hidden_th">
                                        {{ trans('others.client_label') }}
                                    </th>

                                    <th class="">
                                        {{ trans('others.product_code_label') }}
                                    </th>

                                    <th class="">
                                        {{ trans('others.product_name_label') }}
                                    </th>
                                    <th class="hidden_th">
                                        {{ trans('others.invoice_no_label') }}
                                    </th>

                                    <th>
                                        {{ trans('others.quantity_label') }}
                                    </th>
                                    <th>
                                        {{ trans('others.unit_price_per_kg_label') }}
                                    </th>

                                    <th>
                                        {{ trans('others.total_price_label') }}
                                    </th>
                                    <th class="hidden_th">
                                        {{ trans('others.bonus_label') }}
                                    </th>

                                    @foreach($vattaxes as $vattax)
                                    <th class="">
                                        {{ $vattax->name }}
                                    </th>
                                    @endforeach

                                    <th class="hidden_th">
                                        Date
                                    </th>
                                    <th class="hidden_th">
                                        Chart of Acc
                                    </th>
                                    <th class="hidden_th">
                                        Due Amount
                                    </th>
                                    <th class="">
                                       Action
                                    </th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="purchase_table_input_row">
                            <?php $i=0;?>
                            @foreach($data as $val)
                                <tr id="purchase_table_row">
                                        <th class="product_group_th hidden_th">
                                         <input type="hidden" name="product_group[]" value=" {{ $val['product_group'] }}">
                                             {{ $val['product_group'] }}
                                        </th>

                                        <th class="client_th hidden_th">
                                         <input type="hidden" name="client_id[]" value="  {{ $val['client'] }}">
                                            {{ $val['client'] }}
                                        </th>

                                        <th class="product_code_th">
                                         <input type="hidden" name="product_code[]" value="{{ $val['product_code'] }}">
                                            {{ $val['product_code'] }}
                                        </th>
                                         <th class="product_th">
                                          <input type="hidden" name="product_id[]" value=" {{ $val['product'] }}">
                                            {{ $val['product_name'] }}
                                        </th>
                                        <th class="invoice_th hidden_th">
                                         <input type="hidden" name="invoice[]" value=" {{ $val['invoice'] }}">
                                            {{ $val['invoice'] }}
                                        </th>

                                        <th class="quantity_th">
                                         <input type="hidden" name="quantity[]" value=" {{ $val['quantity'] }}">
                                            {{ $val['quantity'] }}
                                        </th>

                                        <th class="price_th">
                                         <input type="hidden" name="price[]" value=" {{ $val['price'] }}">
                                            {{ $val['price'] }}
                                        </th>

                                         <th class="total_price_th">
                                          <input type="hidden" name="total_price[]" value="{{ $val['total_price'] }}">
                                            {{ $val['total_price'] }}
                                        </th>

                                        <th class="bonus_th hidden_th">
                                         <input type="hidden" name="bonus[]" value=" {{ $val['bonus'] }}">
                                            {{ $val['bonus'] }}
                                        </th>

                                        <th class="vat_th">
                                         <input name="vat_id[]" type="hidden" value="2">
                                          <input type="hidden" name="vat_percentage[]" value=" {{$val['vat_percentage']}}">
                                          {{$val['vat_percentage']}}
                                        </th>
                                        <th class="tax_th">
                                        <input name="tax_id[]" type="hidden" value="3">
                                          <input type="hidden" name="tax_percentage[]" value=" {{$val['tax_percentage']}}">
                                          {{$val['tax_percentage']}}
                                        </th>

                                        <th class="date_th hidden_th">
                                         <input type="hidden" name="date[]" value="  {{ $val['date'] }}">
                                            {{ $val['date'] }}
                                        </th>

                                        <th class="chart_fo_acc_th hidden_th">
                                         <input type="hidden" name="chart_of_acc[]" value="{{ $val['chart_of_acc'] }}">
                                            {{ $val['chart_of_acc'] }}
                                        </th>

                                        <th class="due_amount_th hidden_th">
                                         <input type="hidden" name="due_amount[]" value=" {{ $val['due_amount'] }}">
                                            {{ $val['due_amount'] }}
                                        </th>

                                        <th class="delete_th">
                                            <button class="delete btn btn-success" type="button" value="<?php echo $i;?>">Remove</button>
                                        </th>
                                </tr>
                             <?php $i++;?>
                            @endforeach    
                            </div>
                        </tbody>
                    </table>
                    <div class="amount_calculatioin">
                        <?php $total_price=0; ?>
                        <div class="cal_row">
                            <label>{{ trans('others.amount_label') }}: </label>
                            @foreach($data as $val)
                                <?php $total_price+= $val['price']*$val['quantity'];?>
                            @endforeach
                            <p id="cal_amount">
                               {{$total_price}} 
                            </p>
                        </div>

                            <?php $total_vat=0; ?>
                            <div class="cal_row">
                                <label>Vat : </label>
                                 @foreach($data as $val)
                                <?php 
                                if($total_vat > 0){
                                
                                }else{
                                  $total_vat = 0;
                                }
                                
                                if($val['vat_percentage'] > 0){
                                
                                }else{
                                  $val['vat_percentage'] = 0;
                                }
                                $total_vat+= ($val['vat_percentage'] * $total_price)/100;?> 
                                @endforeach
                                <p id="cal_vat_{{ $i }}">
                                    {{$total_vat}}
                                </p>
                            </div>

                             <?php $total_tax=0; ?>
                            <div class="cal_row">
                                <label>Tax : </label>
                                 @foreach($data as $val)
                                <?php 
                                if($total_tax > 0){
                                
                                }else{
                                  $total_tax = 0;
                                }
                                
                                if($val['tax_percentage'] > 0){
                                
                                }else{
                                  $val['tax_percentage'] = 0;
                                }
                                $total_tax+= ($val['tax_percentage'] * $total_price)/100;?> 
                                @endforeach
                                <p id="cal_vat_{{ $i }}">
                                    {{$total_tax}}
                                </p>
                            </div>
                        <hr>

                        <div class="cal_row">
                        <?php $final_amount=0; ?>
                            <label>{{ trans('others.final_amount_label') }} : </label>
                            <?php $final_amount += $total_price + $total_vat + $total_tax;?>
                            <p id="cal_final">
                                {{$final_amount}}
                            </p>
                        </div>

                    </div>

                    <?php $i = 0;?>
                    @foreach($vattaxes as $vattax)
                        <input name="tax_vat_id[]" type="hidden" value="{{ $vattax->id }}">
                    <?php $i++;?>
                    @endforeach

                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <input name="company_id" type="hidden" value="{{ $request->session()->get('company_id') }}">
                    <input name="com_group_id" type="hidden" value="{{ $request->session()->get('group_id') }}">
                    <div class="col-md-12" style="padding-right: 0px; margin-top: 10px;">
                    <div class="form-group col-sm-2 pull-right" style="padding-right: 0px;">
                        <input class="form-control input_required btn btn-primary save_btn" style="float: right;" type="submit" value="{{ trans('others.save_button') }}">
                    </div>
                    </div>
                </form>
            </div>

