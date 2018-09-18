<link rel="stylesheet" href="{{ asset("assets/stylesheets/challan.css") }}" />
@if(isset($g_challan[0]->product_name))

<div>
<button onclick='printDiv("page-wrap");' type="button" style="margin:0 0 10px 778px" class="btn btn-info print_y" data-dismiss="modal">{{ trans('others.mxp_print_btn') }}</button>
</div>
@endif
@if(!isset($g_challan[0]->product_name))

        <h2 class="alert alert-danger">
          No result mached
        </h2>
 @endif       
    <div id="page-wrap" class="kol">
         
         @if(isset($g_challan[0]->product_name))


        <h1 id="header">Delivery Challan</h1>
        
        <div id="identity">
        
            <p id="address">Mailling Address:House27,Road-02,Pallabi,Mirpur,Dhaka-1216

Phone:+880028001833
Fax:8001833
Reg.Address:34/1,Pallabi,Mirpur,Dhaka-1216,E-mail:chemaxbd@gmail.com</p>
 <div id="logo">
              <img id="image" src="{{ asset("assets/images/chemax-logo.png") }}" alt="logo" width="100px" height="50px">
              
            </div>
        
        </div>
        
        <div style="clear:both"></div>
        @if(isset($g_challan[0]))
        <div id="customer">
            <h3>Customer</h3>
            <p id="customer-title">{{$g_challan[0]->acc_final_name}}</p>

            <table id="meta">
                <tr>
                    <td class="meta-head">Order No</td>
                    <td><span>{{$g_challan[0]->order_no}}</span></td>
                </tr>
                <tr>

                    <td class="meta-head">Date</td>
                    <td><span id="date">{{$g_challan[0]->sale_date}}</span></td>
                </tr>

            </table>
        
        </div>
        @endif
        <table id="items">
        
          <tr>
              <th>Sl</th>
              <th>Item</th>
              <th>Description</th>
              <th>Quantity</th>
              <th>Unit</th>
          </tr>
<?php $i=0;$qtn=0;$unt="";?>         
@foreach($g_challan as $row)
<?php $i++;$qtn+=$row->quantity;$unt=$row->unit;?>
<tr class="item-row">
              <td><span class="cost">{{$i}}</span></td>
              <td class="item-name"><div class="delete-wpr"><span>{{$row->product_name}}</span></div></td>
              <td class="description"><span></span></td>
              <td><span class="qty">{{$row->quantity}}</span></td>
              <td><span class="qty">{{$row->unit}}</span></td>
          </tr>

@endforeach
                  <tr id="hiderow">
                    <td colspan="5"><a id="addrow" href="javascript:;"></a></td>
                  </tr>
                  
                   
        </table>
        <div style="float:right;">
          <h5>Total Quantity:<?php echo $qtn;echo $unt;?></h5> 
        </div>
                 
        
    
    </div>
 
  @endif

