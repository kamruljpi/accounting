<div id="myModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true" style="position: fixed;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">{{ $ledger[0]->description }}</h3>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-left: 10px;" id="DivIdToPrint">
                    <div class="col-md-6">
                        <p><strong>Cleint Details   :</strong>   {{ $ledger[0]->client_details }}</p>
                        <p><strong>Chart of Account   :</strong>   {{ $ledger[0]->acc_final_name }}</p>
                        <p><strong>Product Details   :</strong>   {{ $ledger[0]->productDetails }}</p>
                        <p><strong>Sales man   :</strong>   {{ $ledger[0]->sales_man_id }}</p>
                        <p><strong>Journal Date   :</strong>   {{ $ledger[0]->journal_date }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Ledger Particular   :</strong>   {{ $ledger[0]->particular }}</p>
                        <p><strong>Debit  :</strong>   {{ $ledger[0]->transaction_amount_debit}}</p>
                        <p><strong>Credit  :</strong>   {{ $ledger[0]->transaction_amount_credit}}</p>
                        <p><strong>Bank name   :</strong>   {{ $ledger[0]->bank_id }}</p>
                        <p><strong>Branch name  :</strong>   {{ $ledger[0]->branch_id }}</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button onclick='printDiv("DivIdToPrint");' type="button" class="btn btn-info print_y" data-dismiss="modal">{{ trans('others.mxp_print_btn') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>