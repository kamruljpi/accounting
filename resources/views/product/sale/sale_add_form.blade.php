 <div class="purchase_table col-md-12">
            <div class="frm_value"></div>
                <form action="{{ Route('product_sale_add_action') }}" method="post" role="form" id="form_val" >
                    <table class="table table-bordered purchase_table" id="tblSearch">
                        <thead>
                            <tr>
                                    <th class=""> Order no </th>
                                    <th class="hidden_th"> Transport no </th>
                                    <th class="hidden_th"> Product group </th>
                                    <th class="hidden_th"> Chart of Acc </th>
                                    <th class=""> Sales date </th>
                                    <th class=""> Product </th>
                                    <th class=""> Transport name </th>
                                    <th class=""> Transport date </th>
                                    <th class=""> Invoice </th>
                                    <th class=""> Client </th>
                                    <th class=""> Quantity </th>
                                    <th class=""> price/unit </th>
                                    <th class=""> due </th>
                                    <th class=""> Bonus </th>
                                    <th class="">
                                        vat
                                    </th>
                                   <th class=""> Action </th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <div class="purchase_table_input_row">
                           <?php $i=0;?>
                          @foreach($data as $row)

                                <tr id="purchase_table_row">
                                   <th class="order_no_th" style=" text-align: middle;">
                                    
                                        {{$row['order_no']}}
                                    </th>

                                    <th class="transport_no_th hidden_th" style=" text-align: middle;">
                                    <input type="hidden" name="transport_no[]" value=" {{$row['transport_no']}}">
                                        {{$row['transport_no']}}
                                    </th>

                                    <th class="product_group_th hidden_th">
                                    <input type="hidden" name="product_group[]" value=" {{$row['product_group']}}">
                                        {{$row['product_group']}}
                                    </th>

                                    <th class="chart_of_acc_th hidden_th">
                                    <input type="hidden" name="chartOfAcc[]" value=" {{$row['chart_of_acc']}}">
                                    <input type="hidden" name="chartOfAccData[]" value=" {{$row['chartOfAcc']}}">
                                        {{$row['chart_of_acc']}}
                                    </th>

                                    <th class="sales_date_th">
                                    <input type="hidden" name="sales_date[]" value=" {{$row['sales_date']}}">
                                        {{$row['sales_date']}}
                                    </th>

                                    <th class="product_th">
                                    <input type="hidden" name="product_id[]" value=" {{$row['product']}}">
                                    <input type="hidden" name="product_name[]" value=" {{$row['product_name']}}">
                                        {{$row['product_name']}}
                                    </th>

                                    <th class="transport_name_th">
                                    <input type="hidden" name="transport_name[]" value=" {{$row['transport_name']}}">
                                        {{$row['transport_name']}}
                                    </th>

                                    <th class="transport_date_th">
                                    <input type="hidden" name="transport_date[]" value=" {{$row['transport_date']}}">
                                        {{$row['transport_date']}}
                                    </th>

                                    <th class="invoice_th">
                                    <input type="hidden" name="invoice_no[]" value=" {{$row['invoice']}}">
                                        {{$row['invoice']}}
                                    </th>

                                    <th class="client_th">
                                    <input type="hidden" name="client_id[]" value=" {{$row['client']}}">
                                        {{$row['client_name']}}
                                    </th>

                                    <th class="quantity_th">
                                    <input type="hidden" name="quantity[]" value=" {{$row['quantity']}}">
                                        {{$row['quantity']}}
                                    </th>

                                    <th class="sales_price_th">
                                    <input type="hidden" name="sales_price[]" value=" {{$row['sales_price']}}">
                                        {{$row['sales_price']}}
                                    </th>

                                    <th class="available_th hidden_th">
                                    <input type="hidden" name="available[]" value=" {{$row['available']}}">
                                        {{$row['available']}}
                                    </th>
                                    <th class="due_th">
                                    <input type="hidden" name="due[]" value="{{$row['total_p_amount']-$row['paid']}}">
                                        {{ $row['total_p_amount'] - $row['paid'] }}
                                    </th>

                                    <th class="bonus_th">
                                    <input type="hidden" name="bonus[]" value=" {{$row['bonus']}}">
                                        {{$row['bonus']}}
                                    </th> 

                                    <th class="vat_th">
                                    <input type="hidden" name="vattax_percentage[]" value=" {{$row['vattax_percentage']}}">
                                        {{$row['vattax_percentage']}}
                                    </th>
                                    <th class="cancel_th">
                                    <button class="cancels btn btn-success" type="button" value="<?php echo $i;?>">Remove</button>
                                    </th>
                                </tr>                                               
                            <?php $i++; ?>
                            @endforeach
                            </div>
                        </tbody>
                        
                    </table>

                    <div class="amount_calculatioin">
                        <?php $total_price=0; ?>
                        <div class="cal_row">
                        @foreach($data as $row)
                            <?php $total_price+= $row['total_p_amount'];?> 
                        @endforeach
                            <label>Amount : </label>
                            <p id="cal_amount">{{$total_price}}</p>
                        </div>

                        <div class="cal_row">
                         <?php $total_bonus=0; ?>
                            <label>Bonus : </label>
                            @foreach($data as $row)
                                <?php
                                if($total_bonus > 0){
                                
                                }else{
                                  $total_bonus = 0;
                                }
                                
                                if($row['bonus'] > 0){
                                
                                }else{
                                  $row['bonus'] = 0;
                                }
                                $total_bonus+= $row['bonus']?> 
                            @endforeach
                            <p id="bonus_amount">{{$total_bonus}}</p>
                        </div>

                         <div class="cal_row">
                         <?php $total_vat_tax=0;$due=0; ?>
                            <label>vat : </label>
                            @foreach($data as $row)
                                <?php 
                                if($total_vat_tax > 0){
                                
                                }else{
                                  $total_vat_tax = 0;
                                }
                                
                                if($row['vattax_percentage'] > 0){
                                
                                }else{
                                  $row['vattax_percentage'] = 0;
                                }
                                $total_vat_tax+= ($row['vattax_percentage'] *  $total_price)/100;?> 
                            @endforeach
                            <p id="cal_vat_tax_{{ $i }}">{{$total_vat_tax}}</p>
                        </div>
                    <div class="cal_row">
                        @foreach($data as $row)
                            <?php $due+= $row['total_p_amount']-$row['paid'];?> 
                        @endforeach
                            <label>Due : </label>
                            <p id="cal_amount">{{$due}}</p>
                        </div>

                        <hr>

                        <div class="cal_row">
                        <?php $final_amount=0; ?>
                            <label>Final Paid Amount : </label>
                             <?php $final_amount += $total_price + $total_bonus + $total_vat_tax-$due;?> 
                            <p id="cal_final">{{$final_amount}}</p>
                        </div>

                    </div>
                    <?php $i = 0;?>
                    @foreach($vattaxes as $vattax)
                        <input name="tax_vat_id[]" type="hidden" value="{{ $vattax->id }}">
                    <?php $i++;?>
                    @endforeach
                    
                    <input type="hidden" name="order_no" value=" {{$data[0]['order_no']}}">
                    <input required name="_token" type="hidden" value="{{ csrf_token() }}">
                    <input required name="company_id" type="hidden" value="{{ $request->session()->get('company_id') }}">
                    <input required name="com_group_id" type="hidden" value="{{ $request->session()->get('group_id') }}">
                    <div class="col-md-12" style="padding-right: 0px; margin-top: 10px;">
                        <div class="form-group col-sm-2 pull-right" style="padding-right: 0px;">
                            <input required class="form-control input_required btn btn-primary sale_save_btn" style="float: right;" type="submit"  value="Submit" >
                        </div>
                    </div>    
                </form>
            </div>
        </div>
    </div>   
