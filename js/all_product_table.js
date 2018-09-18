$(document).ready(function(){
	// LC Purchase Table input 
	$('#myModal').modal({backdrop: 'static', keyboard: false}) 
	
	$(".purchase_table_right input").keyup(function(){

		var lc_vat = $('input[name=vat]').val();
		var bank_commission = $('input[name=bank_commission]').val();
		
		if(bank_commission>-1 && lc_vat>-1){
			
			var totalVat = (bank_commission/100)*lc_vat;
			// alert();
			$("input[name=total_vat]").val(totalVat);
		}else{
			$("input[name=total_vat]").val("");
		}


		var lc_amount = $('input[name=lc_amount]').val();
		var doller_rate = $('input[name=doller_rate]').val();

		if(doller_rate>-1 && lc_amount>-1){
			$("input[name=lc_amount_in_taka]").val(doller_rate*lc_amount);
		}else{
			$("input[name=lc_amount_in_taka]").val("");
		}

		var margin = $('input[name=margin]').val();

		if(margin>-1 && lc_amount>-1){
			$("input[name=total_margin]").val((lc_amount/100)*margin);
		}else{
			$("input[name=total_margin]").val("");
		}
		
		var total_margin = $('input[name=total_margin]').val();
		if(total_margin>-1){
			$('input[name=total_margin_in_taka]').val(total_margin*doller_rate);
		}else{
			$('input[name=total_margin_in_taka]').val("");
		}

		var total_paid_amount_with_other = parseFloat($('input[name=swift_charges]').val())+
									 +parseFloat($('input[name=bank_commission]').val())+
									 +parseFloat($('input[name=total_vat]').val())+
									 +parseFloat($('input[name=application_form_charges]').val())+
									 +parseFloat($('input[name=lc_amount_in_taka]').val())+
									 +parseFloat($('input[name=total_margin_in_taka]').val())+
									 -parseFloat($('input[name=due_payment_in_taka]').val())+
									 +((parseFloat($('input[name=other_cost]').val()))? (parseFloat($('input[name=other_cost]').val())) : 0 );
		// By nabodip
		var bank_cost = parseFloat($('input[name=swift_charges]').val())+
									 +parseFloat($('input[name=bank_commission]').val())+
									 +parseFloat($('input[name=total_vat]').val())+
									 +parseFloat($('input[name=application_form_charges]').val());
		// alert(bank_cost);
		var total_paid_amount = bank_cost+
									 +parseFloat($('input[name=total_margin_in_taka]').val())+
									 +((parseFloat($('input[name=other_cost]').val()))? (parseFloat($('input[name=other_cost]').val())) : 0 );
		
		// alert(total_paid_amount);
		// alert(parseFloat($('input[name=lc_amount_in_taka]').val()));
		var due_payment_with_cost_in_tk = (parseFloat($('input[name=lc_amount_in_taka]').val())+
									 - +parseFloat($('input[name=total_margin_in_taka]').val()));

		$('input[name=due_payment]').val(due_payment_with_cost_in_tk/doller_rate);
		$('input[name=due_payment_in_taka]').val(due_payment_with_cost_in_tk);		
	});
	

	$("#new_lc_purchase_table_row").click(function(){

			var client_id = $('select[name=client]').find(":selected").val();
			var product_group = $('select[name=product_group]').find(":selected").val();
			var product_id = $('select[name=product]').find(":selected").val();
			if(product_id != null){
				var pro_id_code = product_id.split(",");
		        var pro_id = pro_id_code[0];
		        var pro_code = pro_id_code[1];
			}
			else{
				alert(" All requitred field are not filled.\n Please Recheck");
				return;
			}	
			

	        if(
	        	$('select[name=client]').find(":selected").text() != "" &&
				$('input[name=date]').val() != "" &&
				$('select[name=product_group]').find(":selected").text() != "" &&
				$('select[name=product]').find(":selected").text() != "" &&
				$('input[name=lc_no]').val() != "" &&
				$('select[name=lc_type]').find(":selected").text() != "" &&
				$('select[name=lc_open_bank]').find(":selected").text() != "" &&
				$('select[name=lc_loan_bank]').find(":selected").text() != "" &&
				$('input[name=lc_amount]').val() != "" &&
				$('input[name=total_margin]').val() != "" &&
				$('input[name=due_payment]').val() != "" &&
				$('input[name=quantity]').val() != "" &&
				$('input[name=country_of_origin]').val() != ""
				){

					$(".client_th").append('<input type="text" name="client[]" readonly value="'+$('select[name=client]').find(":selected").text()+'" />');
					$(".client_id_th").append('<input type="text" name="client_id[]" readonly value="'+client_id+'" />');
					$(".date_th").append('<input type="text" name="date[]" readonly value="'+$('input[name=date]').val()+'" />');
					$(".invoice_th").append('<input type="text" name="invoice[]" readonly value="'+$('input[name=invoice]').val()+'" />');
					$(".product_group_th").append('<input type="text" name="product_group[]" readonly value="'+$('select[name=product_group]').find(":selected").text()+'" />');
					$(".product_group_id_th").append('<input type="text" name="product_group_id[]" readonly value="'+product_group+'" />');
					$(".product_th").append('<input type="text" name="product[]" readonly value="'+$('select[name=product]').find(":selected").text()+'" />');
					$(".product_id_th").append('<input type="text" name="product_id[]" readonly value="'+pro_id+'" />');
					$(".product_code_th").append('<input type="text" name="product_code[]" readonly value="'+pro_code+'" />');
					$(".lc_no_th").append('<input type="text" name="lc_no[]" readonly value="'+$('input[name=lc_no]').val()+'" />');
					$(".lc_type_th").append('<input type="text" name="lc_type[]" readonly value="'+$('select[name=lc_type]').find(":selected").text()+'" />');
					$(".bank_name_th").append('<input type="text" name="lc_open_bank[]" readonly value="'+$('select[name=lc_open_bank]').val()+'" />');
					$(".loan_bank_th").append('<input type="text" name="lc_loan_bank[]" readonly value="'+$('select[name=lc_loan_bank]').val()+'" />');
					$(".swift_charges_th").append('<input type="text" name="swift_charges[]" readonly value="'+$('input[name=swift_charges]').val()+'" />');
					$(".vat_th").append('<input type="text" name="vat[]" readonly value="'+$('input[name=vat]').val()+'" />');
					$(".bank_commission_th").append('<input type="text" name="bank_commission[]" readonly value="'+$('input[name=bank_commission]').val()+'" />');
					$(".total_vat_th").append('<input type="text" name="total_vat[]" readonly value="'+$('input[name=total_vat]').val()+'" />');
					$(".application_form_charges_th").append('<input type="text" name="application_form_charges[]" readonly value="'+$('input[name=application_form_charges]').val()+'" />');
					
					$(".lc_amount_th").append('<input type="text" name="lc_amount_in_taka[]" readonly value="'+$('input[name=lc_amount_in_taka]').val()+'" />');
					$(".lc_amount_doller_th").append('<input type="text" name="lc_amount[]" readonly value="'+$('input[name=lc_amount]').val()+'" />');
					$(".doller_rate_th").append('<input type="text" name="doller_rate[]" readonly value="'+$('input[name=doller_rate]').val()+'" />');
					$(".in_taka_th").append('<input type="text" name="in_taka[]" readonly value="'+$('input[name=in_taka]').val()+'" />');

					$(".margin_th").append('<input type="text" name="margin[]" readonly value="'+$('input[name=margin]').val()+'" />');
					$(".quantity_th").append('<input type="text" name="quantity[]" readonly value="'+$('input[name=quantity]').val()+'" />');
					$(".lc_status_th").append('<input type="text" name="lc_status[]" readonly value="'+$('select[name=lc_Status]').val()+'" />');
					$(".chart_of_acc_th").append('<input type="text" name="chart_of_acc[]" readonly value="'+$('select[name=chart_of_acc]').val()+'" />');

					$(".total_margin_th").append('<input type="text" name="total_margin_in_taka[]" readonly value="'+$('input[name=total_margin_in_taka]').val()+'" />');
					$(".due_payment_th").append('<input type="text" name="due_payment_in_taka[]" readonly value="'+$('input[name=due_payment_in_taka]').val()+'" />');
					$(".other_cost_th").append('<input type="text" name="other_cost[]" readonly value="'+$('input[name=other_cost]').val()+'" />');
					$(".country_of_origin_th").append('<input type="text" name="country_of_origin[]" readonly value="'+$('input[name=country_of_origin]').val()+'" />');


					if( $('input[name=total_vat]').val() == 0 ){
						var total_paid_amount_with_other = parseFloat($('input[name=lc_amount_in_taka]').val())+
												 			+parseFloat($('input[name=total_margin_in_taka]').val())+
												 			-parseFloat($('input[name=due_payment_in_taka]').val())+
												 			+((parseFloat($('input[name=other_cost]').val()))? (parseFloat($('input[name=other_cost]').val())) : 0 );
					}
					else{
						var total_paid_amount_with_other = parseFloat($('input[name=swift_charges]').val())+
												 		+parseFloat($('input[name=bank_commission]').val())+
												 		+parseFloat($('input[name=total_vat]').val())+
												 		+parseFloat($('input[name=application_form_charges]').val())+
												 		+parseFloat($('input[name=lc_amount_in_taka]').val())+
												 		+parseFloat($('input[name=total_margin_in_taka]').val())+
												 		-parseFloat($('input[name=due_payment_in_taka]').val())+
												 		+((parseFloat($('input[name=other_cost]').val()))? (parseFloat($('input[name=other_cost]').val())) : 0 );
					}
					// By nabodip
					if ($('input[name=total_vat]').val() == 0 ) {
						var bank_cost = 0;
					}
					else{
					var bank_cost = parseFloat($('input[name=swift_charges]').val())+
												 +parseFloat($('input[name=bank_commission]').val())+
												 +parseFloat($('input[name=total_vat]').val())+
												 +parseFloat($('input[name=application_form_charges]').val());
					}
					// alert(bank_cost);
					var total_paid_amount = bank_cost+
												 +parseFloat($('input[name=total_margin_in_taka]').val())+
												 +((parseFloat($('input[name=other_cost]').val()))? (parseFloat($('input[name=other_cost]').val())) : 0 );
					
					// alert(total_paid_amount);
					// alert(parseFloat($('input[name=lc_amount_in_taka]').val()));
					
					var due_payment_with_cost_in_tk = (parseFloat($('input[name=lc_amount_in_taka]').val())+
												 - +parseFloat($('input[name=total_margin_in_taka]').val()));

					var due_payment_without_cost_in_tk = due_payment_with_cost_in_tk - ((parseFloat($('input[name=other_cost]').val()))? (parseFloat($('input[name=other_cost]').val())) : 0 );
					// alert(due_payment_in_tk);
					// alert(total_paid_amount);
					// alert(due_payment_in_tk - total_paid_amount);
					$(".total_paid_amount_with_other_th").append('<input type="text" name="total_paid_amount_with_other[]" readonly value="'+(total_paid_amount).toFixed(2)+'" />');
					$(".total_paid_amount_without_other_th").append('<input type="text" name="total_paid_amount_without_other[]" readonly value="'+(((total_paid_amount).toFixed(2)) - ((parseFloat($('input[name=other_cost]').val()))? (parseFloat($('input[name=other_cost]').val())) : 0 )).toFixed(2)+'" />');

					// end
					// $(".total_paid_amount_with_other_th").append('<input type="text" name="total_paid_amount_with_other[]" readonly value="'+total_paid_amount_with_other.toFixed(2)+'" />');
					// $(".total_paid_amount_with_other_th").append('<input type="text" name="total_paid_amount_with_other[]" readonly value="'+total_paid_amount_with_other.toFixed(2)+'" />');

					if( $('input[name=total_vat]').val() == 0){
						var total_paid_amount_without_other = parseFloat($('input[name=lc_amount_in_taka]').val())+
												 +parseFloat($('input[name=total_margin_in_taka]').val())+
												 -parseFloat($('input[name=due_payment_in_taka]').val());
					}
					else{
						var total_paid_amount_without_other = parseFloat($('input[name=swift_charges]').val())+
												 +parseFloat($('input[name=bank_commission]').val())+
												 +parseFloat($('input[name=total_vat]').val())+
												 +parseFloat($('input[name=application_form_charges]').val())+
												 +parseFloat($('input[name=lc_amount_in_taka]').val())+
												 +parseFloat($('input[name=total_margin_in_taka]').val())+
												 -parseFloat($('input[name=due_payment_in_taka]').val());
					}
					// $(".total_paid_amount_without_other_th").append('<input type="text" name="total_paid_amount_without_other[]" readonly value="'+total_paid_amount_without_other.toFixed(2)+'" />');

					var final_amount_with_other =0;
					$('input[name^="total_paid_amount_with_other"]').each(function() {
					    final_amount_with_other =  final_amount_with_other+parseFloat($(this).val());
					});
					$("#final_amount_with_other_in_doller").text((final_amount_with_other/parseFloat($('input[name=doller_rate]').val())).toFixed(2));

					$("#final_amount_with_other_intaka").text(final_amount_with_other.toFixed(2));




					var final_amount_without_other =0;
					$('input[name^="total_paid_amount_without_other"]').each(function() {
						// alert(parseFloat($(this).val()));
					    final_amount_without_other =  final_amount_without_other+parseFloat($(this).val());
					});
					$("#final_amount_without_other_intaka").text(final_amount_without_other.toFixed(2));

					$("#final_amount_without_other_in_doller").text(final_amount_without_other/parseFloat($('input[name=doller_rate]').val()).toFixed(2));

					$('.save_btn').removeAttr("disabled");
			}
			else{
				alert(" All requitred field are not filled.\n Please Recheck");
				return;
			}

	});


	// LC Purchase Table input 
});
$('.bank_info_btn').on('click', function(){
    $('.input_table_specific').css('display','block');
    $('.bank_info_btn').css('display','none');
});

var pre_paid_due_in_taka 		= 	((parseFloat($('input[name=paid_due_in_taka]').val()))? (parseFloat($('input[name=paid_due_in_taka]').val())) : 0 );
var pre_total_margin 			= 	((parseFloat($('input[name=total_margin]').val()))? (parseFloat($('input[name=total_margin]').val())) : 0 );
var pre_margin 					= 	((parseFloat($('input[name=margin]').val()))? (parseFloat($('input[name=margin]').val())) : 0 );
var pre_total_margin_in_taka 	= 	((parseFloat($('input[name=total_margin_in_taka]').val()))? (parseFloat($('input[name=total_margin_in_taka]').val())) : 0 );
var pre_due_payment 			= 	((parseFloat($('input[name=due_payment]').val()))? (parseFloat($('input[name=due_payment]').val())) : 0 );
var pre_due_payment_in_taka 	= 	((parseFloat($('input[name=due_payment_in_taka]').val()))? (parseFloat($('input[name=due_payment_in_taka]').val())) : 0 );

$('#margin').keyup(function(ev) {
	var margin = $('input[name=margin]').val();
	if(margin<=100)
	{
		var total_payable = $('input[name=lc_amount_in_taka]').val();
	    $("#paid_due_in_taka").prop("disabled", true);
		$('input[name=paid_due_in_taka]').val((total_payable*margin)/100);
	}
	else
	{
    	alert("Margin can't more than 100%")
    	$('input[name=margin]').val(pre_margin);
    	$('input[name=paid_due_in_taka]').val(pre_paid_due_in_taka);
    	$('input[name=total_margin]').val(pre_total_margin);
    	$('input[name=total_margin_in_taka]').val(pre_total_margin_in_taka);
    	$('input[name=due_payment]').val(pre_due_payment);
    	$('input[name=due_payment_in_taka]').val(pre_due_payment_in_taka);
	}
})

$('#paid_due_in_taka').on('keyup', function(ev) {
    $("#margin").prop("disabled", true);

    var paid_due_in_taka 	= 	((parseFloat($('input[name=paid_due_in_taka]').val()))? (parseFloat($('input[name=paid_due_in_taka]').val())) : 0 );
    var lc_amount 			= 	((parseFloat($('input[name=lc_amount]').val()))? (parseFloat($('input[name=lc_amount]').val())) : 0 );
    var doller_rate 		= 	((parseFloat($('input[name=doller_rate]').val()))? (parseFloat($('input[name=doller_rate]').val())) : 0 );

    // alert(paid_due_in_taka);

    if((paid_due_in_taka+pre_total_margin)<=(lc_amount*doller_rate))
    {
		$('input[name=margin]').val((((paid_due_in_taka/doller_rate)+pre_total_margin)/lc_amount)*100);
		$('input[name=total_margin]').val((paid_due_in_taka/doller_rate)+pre_total_margin);
		$('input[name=total_margin_in_taka]').val(paid_due_in_taka+(pre_total_margin*doller_rate));
		$('input[name=due_payment]').val(lc_amount-(paid_due_in_taka/doller_rate)-pre_total_margin);
		$('input[name=due_payment_in_taka]').val((lc_amount*doller_rate) - (paid_due_in_taka+(pre_total_margin*doller_rate)));
    }
    else
    {
    	alert("Paid due can't be greater than Due payment")
    	$('input[name=paid_due_in_taka]').val('');
    	$('input[name=margin]').val(pre_margin);
    	$('input[name=total_margin_in_taka]').val(pre_total_margin_in_taka);
    	$('input[name=due_payment]').val(pre_due_payment);
    	$('input[name=due_payment_in_taka]').val(pre_due_payment_in_taka);
    }    
});



  $('#total_price').val($('input[name=quantity]').val()*$('input[name=price]').val());
  var total_price = parseInt($('#total_price').val());

  var total_vat = 0;

  $('.different_input_field input[name^="vattax_percentage"]').each(function() {
    var vat_tax = $(this).val();

    total_vat += (total_price*vat_tax)/100;
  });
    // alert(i);


    // var vat_tax = $('input[name="vattax_percentage[]"]').val();
    //   // total_vat += (total_price*vat_tax)/100;
    // alert(vat_tax);
    // alert(total_vat);

  $('#total_price_with_vat_tax').val(total_price+total_vat);

  var due_amount = $('input[name=total_price_with_vat_tax]').val()-($('input[name=paid_amount]').val());
  $('#due_amount').val(due_amount);

  
$('#ledgerDetailsForm').submit(function(ev){
	ev.preventDefault();
	ajaxFunctionForDetails("/detailsJournalOfLedger",$("#ledgerDetailsForm").serialize());
});