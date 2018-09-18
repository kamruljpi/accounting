<div id="myModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" style="position: fixed;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">{{ $lc_pursache_product[0]->product_details }}</h3>
            </div>
            <div class="modal-body" id="modal-body">
                <div class="row" style="margin-left: 10px;" id="DivIdToPrint">
                    <div class="col-md-6">
                        <p><strong>L/C opening date   :</strong>   {{ $lc_pursache_product[0]->purchase_date }}</p>
                        <p><strong>L/C Type   :</strong>   {{ $lc_pursache_product[0]->lc_type }}</p> 
                        <p><strong>L/C no./id   :</strong>   {{ $lc_pursache_product[0]->lc_no }}</p>
                        <p><strong>Client   :</strong>   {{ $lc_pursache_product[0]->client_details }}</p>
                        <p><strong>Invoice Code   :</strong>   {{ $lc_pursache_product[0]->invoice_code }}</p>
                        <p><strong>Product   :</strong>   {{ $lc_pursache_product[0]->product_details }}</p>
                        <p><strong>Quantity   :</strong>   {{ $lc_pursache_product[0]->quantity }}</p>
                        <p><strong>Orign Country   :</strong>   {{ $lc_pursache_product[0]->country_orign }}</p>
                        <p><strong>Bank swift charge(taka)  :</strong>   {{ $lc_pursache_product[0]->bank_swift_charge }}</p>
                        <p><strong>VAT(taka)   :</strong>   {{ $lc_pursache_product[0]->vat }}</p>
                       <!--  <p><strong>Account Head name :</strong>   {{ $lc_pursache_product[0]->acc_final_name }}</p> -->
                    </div>
                    <div class="col-md-6">
                        <p><strong>Bank Commission(taka)   :</strong>   {{ $lc_pursache_product[0]->bank_commission }}</p>
                        <p><strong>Application Form Charge(taka)  :</strong>   {{ $lc_pursache_product[0]->apk_form_charge}}</p>
                        <p><strong>L/C margin(taka)  :</strong>   {{ $lc_pursache_product[0]->lc_margin}}</p>
                        <p><strong>Due Payment(taka)   :</strong>   {{ $lc_pursache_product[0]->due_payment }}</p>
                        <p><strong>Stock status  :</strong>   {{ ($lc_pursache_product[0]->stock_status == 1)? "stocked":"not yet stocked"}}</p>
                        <p><strong>L/C status  :</strong>   {{ ($lc_pursache_product[0]->lc_status == 1)? "Approved":"Pending"}}</p>
                        <p><strong>Bank Name  :</strong>   {{ $lc_pursache_product[0]->acc_final_name }}</p>
                        <p><strong>Dollar Rate   :</strong>   {{ $lc_pursache_product[0]->dollar_rate }}</p>
                        <p><strong>L/C amount(taka)   :</strong>   {{ $lc_pursache_product[0]->lc_amount_taka }}</p>
                        <p><strong>L/C amount(usd)  :</strong>   {{ $lc_pursache_product[0]->lc_amount_usd}}</p>
                        <p><strong>Others Cost  :</strong>   {{ $lc_pursache_product[0]->others_cost }}</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6">
                           
                            <p><strong>Total amount with other Cost(taka)  :</strong>   {{ $lc_pursache_product[0]->total_amount_with_other_cost}}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total amount without other Cost(taka)  :</strong>   {{ $lc_pursache_product[0]->total_amount_without_other_cost }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total paid(taka)  :</strong>   {{ $lc_pursache_product[0]->total_paid }}</p>
                        </div>
                    </div>

                </div>

            </div>
                    <button onclick='printDiv("modal-body");' type="button" class="btn btn-info print_y" data-dismiss="modal">{{ trans('others.mxp_print_btn') }}</button>

        </div>
    </div>
</div>