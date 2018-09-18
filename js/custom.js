$(document).ready(function(){

  $(".input_required").after(' <span class="req_star">*<span>');

  $('#languageSwitcher').change(function(){
      var locale = $(this).val();
      var _token = $("input[name = _token]").val();
      // alert(_token);

      $.ajax({
          type: "GET",
          url:base_url+"/language",
          data: {locale: locale, _token: _token},
          datatype: 'json',
          cache: false,
          async: false,
          success: function(result) {

              window.location.reload(true);

          },
          error: function(data) {
          },

      });
      $("#languageSwitcher").html(locale);
  });
  $("#quantity, #unit_price").keyup(function(){
   
    var unitPrice=$("#unit_price").val();
    var qtn=$("#quantity").val();
    var lc_amount_doller=qtn*unitPrice;
    $("#lc_amount").val(lc_amount_doller);
    $('#doller_rate').removeAttr("disabled");
    if($("#lc_amount").val()=="" || $("#lc_amount").val()==0){
   $('#doller_rate').attr("disabled");
    }

    });
$("input[name=sales_price], input[name=quantity]").keyup(function(){
    var qtn=$("input[name = quantity]").val();
    var price=$("input[name = sales_price]").val();
    var paid_price=qtn*price;
   $("input[name = total_p_amount]").val(paid_price);
 });
  
});

$(document).ready(function(){

 function load_data(query)
 {
  $.ajax({
   url:base_url+"/search_trans_key",
   type:"GET",
   data:"value="+query,
   success:function(data)
   {
    if(query == ''){
      $("#hide_me").css('display', 'block');
      $('#result').html('');
    }
    else{
      $("#hide_me").css('display', 'none');
      // alert(data);
      $('#result').html(data);
    }
   },
   error: function(er) {
        alert("error");
    }
  });
}

 $('#searchFld').keyup(function(){
    var search = $(this).val();
    load_data(search);

  });
});

function getPermission(role_id){

  $('input:checkbox').removeAttr('checked');
  $(this).val('check all');

  var roleId =role_id.value;

  $.ajax({
      url:base_url+"/super-admin/permissions",
      type:"GET",
      data:"roleId="+roleId,
      cache: false,
      async: false,
      success:function(result){

        if(result.length>0){
           for (i = 0; i < result.length; i++) {
                    document.getElementById(""+result[i]+"").checked = true;
                }
        }
      
        $(".all_checkbox").css("display", "block");
        $('.single_check_box').removeAttr("disabled");

      },
      error:function(result){
        alert("Error");
      }
  });
}


$('.delete_id').click(function() {
  var href = "/_translation/manage";
  var check=confirm('Are you sure to delete the item ?');
  if(check==true){
   window.location.href = href;
  }else{
    return false;
  }
});


var $rows = $('#tblSearch tr');
$('#user_search').on('keyup', function(){
    var string = $(this).val().toLowerCase();

    // var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();


    $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(string);
    }).hide();

     $rows.first().show();
});


$('#companyId').change(function(){
    var comId = $(this).find(':selected').val();
    var _token = $("input[name = _token]").val()

    $("#roleId option").remove();

  $.ajax({
      url:base_url+"/super-admin/getrolelist",
      type:"GET",
      data: {comId: comId, _token: _token},
      datatype: 'json',
      cache: false,
      async: false,
      success:function(result){

        var myObj = JSON.parse(result);

        $('#roleId').append($('<option>', {
            value: "",
            text : "Select Role"
        }));

        for(i in myObj){
              $('#roleId').append($('<option>', {
                  value: myObj[i].id,
                  text : myObj[i].name
              }));
        }



        $('#roleId').removeAttr("disabled");

      },
      error:function(result){
        alert("Error");
      }
  });

});

$('#selectAllcheckBox').click(function(){
    $(".single_check_box").prop('checked', true);
});

$('#unselectAllcheckBox').click(function(){
  $(".single_check_box").prop('checked', false);
});


$('#new_purchase_table_row').click(function(){
    $('.purchase_table tbody>tr:last').clone(true).insertAfter('.purchase_table tbody>tr:last');
});

$('#new_purchase_row_delete').click(function(){
    // alert("delete");
    $(this).closest("tr").remove();
});

$(document).ready(function(){

 function load_data(query)
 {
  // alert(query);
  $.ajax({
   url:base_url+"/search_purchased_client",
   type:"GET",
   data:"value="+query,
   success:function(data)
   {
    // alert(data);
    if(query == ''){
      // $("#hide_me").css('display', 'block');
      $('#clientSearchDiv').html('');
    }
    else{
      // $("#hide_me").css('display', 'none');
      $('#clientSearchDiv').html(data);
    }
   },
   error: function(er) {
        alert("error");
    }
  });
}

 $('#clientSearchFld').keyup(function(){
    var search = $(this).val();
    load_data(search);
      
  });
});

$(document).ready(function(){

 function load_data(query)
 {
  $.ajax({
   url:base_url+"/search_purchased_invoice",
   type:"GET",
   data:"value="+query,
   success:function(data)
   {
    // alert(data);
    if(query == ''){
      $('#invoiceSearchDiv').html('');
    }
    else{
      $('#invoiceSearchDiv').html(data);
    }
   },
   error: function(er) {
        alert("error");
    }
  });
}

 $('#invoiceSearchFld').keyup(function(){
    var search = $(this).val();
    load_data(search);
      
  });
});

function pressClick(client_name, client_id){
  $("#clientSearchFld").val(client_name);
  $("#clientSearchHiden").val(client_id);
  $('#clientSearchDiv').html('');
}

function invoicePressClick(invoice_code){
  $("#invoiceSearchFld").val(invoice_code);
  $('#invoiceSearchDiv').html('');
}



$('#product_group').change(function(){
    var product_group_id = $('select[name=product_group]').val();

    $("#product option").remove();

    $.ajax({
        url:base_url+"/product/get_product_group",
        type:"GET",
        data:'product_group_id='+product_group_id,
        datatype: 'string',
        cache: false,
        async: false,
        success:function(result){

          var myObj = JSON.parse(result);

          $('#product').append($('<option>', {
              value: "",
              text : "Select Product"
          }));

          for(i in myObj){
                $('#product').append($('<option>', {
                    value: myObj[i].id,
                    text : myObj[i].pro_name+" - "+myObj[i].pac_name+"("+myObj[i].quantity+") "+myObj[i].unit_name+"("+myObj[i].unit_quantity+")"
                }));
          }
          $('#product').removeAttr("disabled");
          

        },
        error:function(result){
          alert("ERROR > "+result);
        }
    });
});

$('#pay_amount').keyup(function(){

  var pay_amount = parseInt(($('input[name=pay_amount]').val())? $('input[name=pay_amount]').val():0);
  var root_paid_amount = parseInt($('input[name=root_paid_amount]').val());
  var total_price = parseInt($('input[name=quantity]').val()*$('input[name=price]').val());

  var total_vat = 0;

  $('.different_input_field input[name^="vattax_percentage"]').each(function() {
    var vat_tax = $(this).val();

    total_vat += (total_price*vat_tax)/100;
  });

  $('#total_price_with_vat_tax').val(total_price+total_vat);

  var paid_amount = $('#paid_amount').val(pay_amount+root_paid_amount);

  var due_amount = $('input[name=total_price_with_vat_tax]').val()-($('input[name=paid_amount]').val());
  $('#due_amount').val(due_amount);

  check_purchase_due(due_amount,'.purchase_data_update');
});

$('.purchase_table_left input').keyup(function(){

  $('#total_price').val($('input[name=quantity]').val()*$('input[name=price]').val());
  var total_price = parseInt($('input[name=quantity]').val()*$('input[name=price]').val());

  var total_vat = 0;

  $('.different_input_field input[name^="vattax_percentage"]').each(function() {
    var vat_tax = $(this).val();

    total_vat += (total_price*vat_tax)/100;
  });

  $('#total_price_with_vat_tax').val(total_price+total_vat);
  
  var due_amount = $('input[name=total_price_with_vat_tax]').val()-($('input[name=paid_amount]').val());
  $('#due_amount').val(due_amount);

  check_purchase_due(due_amount,'.purchase_data_add');
});

function check_purchase_due(due, btn_name)
{
  if(due>-1){
    $(btn_name).removeAttr("disabled");
    $('#due_amount').css("border","0px solid transparent");
    
  }else{
    $(btn_name).prop("disabled", true);
    $('#due_amount').css("border","1px solid red");
  }
}

$('#product_group_sale').change(function(){
    var product_group_id = $('select[name=product_group]').val();
    $("#product option").remove();


    $.ajax({
        url:base_url+"/product/get_product_group_sale",
        type:"GET",
        data:'product_group_id='+product_group_id,
        datatype: 'string',
        cache: false,
        async: false,
        success:function(result){
          var myObj = JSON.parse(result);
          $('#product').append($('<option>', {
              value: "",
              text : "Select Product"
          }));

          for(i in myObj){
                $('#product').append($('<option>', {
                    value: myObj[i].pro_id+","+myObj[i].product_code,
                    text : myObj[i].product_name+" - "+myObj[i].packet_name+"("+myObj[i].packet_quantity+") "+myObj[i].unit_name+"("+myObj[i].unit_quantity+")"
                }));
          }
          $('#product').removeAttr("disabled");
          
          

        },
        error:function(result){
          alert("ERROR > "+result);
        }
    });
});

$('#product_group_sale_lc').change(function(){
    var product_group_id = $('select[name=product_group]').val();
    $("#product option").remove();


    $.ajax({
        url:base_url+"/product/get_product_group_sale_lc",
        type:"GET",
        data:'product_group_id='+product_group_id,
        datatype: 'string',
        cache: false,
        async: false,
        success:function(result){
          var myObj = JSON.parse(result);
          $('#product').append($('<option>', {
              value: "",
              text : "Select Product"
          }));

          for(i in myObj){
                $('#product').append($('<option>', {
                    value: myObj[i].pro_id,
                    text : myObj[i].product_name+" - "+myObj[i].packet_name+"("+myObj[i].packet_quantity+") "+myObj[i].unit_name+"("+myObj[i].unit_quantity+")"
                }));
          }
          $('#product').removeAttr("disabled");
          $('#product').change(function(){
            $('input[name=quantity]').removeAttr("readonly");
            $('input[name=sales_price]').removeAttr("readonly");
          });
          
          

        },
        error:function(result){
          alert("ERROR > "+result);
        }
    });
});

//Order_no unique code 
$( document ).ready(function() {
     $.ajax({
        url:base_url+"/product/get_Order_No",
        type:"GET",
        datatype: 'string',
        cache:false,
        async:true,
        success:function(result){
            $("#order_no").val(result);
        },
        error:function(error){
            alert("Error: "+error);
        }
    });
});

var total_bonus = 0;
var invoice_gen_inc = 0;

//after add value,value table show->start 
var invoice_gen_inc = 0;
var counts = 0;
var sl_arr = [];
$('.sale_data_add').click(function(ev){

    //Invoice Gererator increment
    invoice_gen_inc++;

    var transport_no = $('input[name=transport_no]').val();

    var product_group = $('select[name=product_group]').find(":selected").text();
    var chartOfAcc = $('select[name=chart_of_acc]').find(":selected").val();
    var product_group_id = $('select[name=product_group]').val();
    var product = $('select[name=product]').find(":selected").text();
    var product_details = $('select[name=product]').val();

    var sales_date = $('input[name=sales_date]').val();
    
    var transport_name = $('input[name=transport_name]').val();
    var transport_date = $('input[name=transport_date]').val();
    var invoice_no = $('input[name=invoice]').val();

    var client = $('select[name=client]').find(":selected").text();
    var client_id = $('select[name=client]').val();

    var quantity = $('input[name=quantity]').val();
    var sales_price = $('input[name=sales_price]').val();
    var vattax_percentage = $('input[name=vattax_percentage]').val();
    var bonus = $('input[name=bonus]').val();


      if(
          product_group != "" &&
          product != "" &&
          product_details != "" &&
          sales_date != "" &&
          transport_name != "" &&
          transport_date != "" &&
          invoice_no != "" &&
          client != "" &&
          client_id != "" &&
          quantity != "" &&
          sales_price != "" &&
          chartOfAcc != "")
      {
          goo(null);
      }

      else{
          var varName = "";

          if(order_no == ""){ varName += "Order No\n" }
          if(transport_no == ""){ varName += "Transport No\n" }

          if(product_group_id == ""){ varName += "Product Group\n" }
          if(product_details == ""){ varName += "Product Name\n" }
          if(client_id == ""){ varName += "Client Name\n" }
          if(invoice_no == ""){ varName += "Invoice No\n" }
          if(quantity == ""){ varName += "Quantity\n" }
          if(sales_date == ""){ varName += "Sale Date\n" }
          if(sales_price== ""){ varName += "Sales Price\n" }
          
          if(product_group== ""){ varName += "Product Group\n" }
          if(transport_name== ""){ varName += "Transport Name\n" }
          if(transport_date== ""){ varName += "Transport Date\n" }
          if(chartOfAcc== ""){ varName += "Chart Of Acc\n" }
          alert(varName+ " \nFill these box.");
      }

  ev.preventDefault();


});

var gj=0;
function goo(ind=null,val){

if(ind!= null){
  //alert(ind);
    sl_arr.splice(ind,1);
    //console.log(sl_arr);
    var token = $("input[name = _token]").val();
    var arr_value = JSON.stringify(sl_arr);
  }

  else{

    var valuesq = $("#frm_value").serializeArray();
     var p_chk=valuesq[5].value;
    var t= $(".p_name").find(":selected").text();
    var client_name = $('select[name=client]').find(":selected").text();
    var chart_acc = $(".chart_acc").find(":selected").text();
    sl_arr.push(valuesq);
    valuesq.push({"name":"product_name","value":t});
    valuesq.push({"name":"chartOfAcc","value":chart_acc});
    valuesq.push({"name":"client_name","value":client_name});
    var token = $("input[name = _token]").val();
    var arr_value = JSON.stringify(sl_arr);

}
  
$.ajax({
        type: "POST",
        url:base_url+"/product/getFromValue",
        datatype:'json',
        cache:false,
        data: {_token: token,arr_val: arr_value},
        success: function (data) {
          $(".frm_value").html(data);
          //console.log(data);
        },
        error: function (data) {
         
        }
    }); 

};
//after add value,value table show->end


//value table remove button  
 $(document).on('click', '.cancels', function() {
  //console.log(sl_arr);
  var val = $(this).val();
  goo(val);
 
});



 //invoice button hide and show 
$("#generate_invoice_btn").hide();

$("#client").change(function(){
  $("#generate_invoice_btn").show();
});

//invoice generation code
var invoice_gen_inc=0;
$("#generate_invoice_btn").on("click", function (ev) {
    ev.preventDefault();
    var product_group_invo = $('select[name=product_group]').find(":selected").text();
    var client_invo = $('select[name=client]').find(":selected").text(); 
    var client_invo_tmp = client_invo.split("",4);
    var invo_prefix =  client_invo_tmp['1']+""+client_invo_tmp['2']+""+client_invo_tmp['3'];
    invo_prefix = invo_prefix.toUpperCase();

    var fullDate = new Date();
    var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : '0' + (fullDate.getMonth()+1);
    
    var year_tmp  = fullDate.getFullYear();

    var year_tmp2 = year_tmp.toString().substring(2, 4);

    var currentDate = year_tmp2 + "" + twoDigitMonth + "" + fullDate.getDate();
    
    var last_sale_id = 0;
    var rnd=Math.floor((Math.random() * 100000) + 1);
    invoice_gen_inc++;
    $("#invoice").val(invo_prefix+currentDate+invoice_gen_inc+rnd);
});



function checkAvailability(){

  var quantity = $('input[name=quantity]').val();
  
if(quantity==""){
  
 $('input[name=bonus]').val("");
 $('input[name=bonus]').prop("disabled","true");
}else{
  $('input[name=bonus]').removeAttr("disabled");
}

  var product_details = $('select[name=product]').val();
  var pro_id_code = product_details.split(",");
  var pro_id = pro_id_code[0];
  var bonus = $('input[name=bonus]').val();
if(bonus==""){
  bonus=0;
}
  $.ajax({
        url:base_url+"/product/check_amount",
        type:"GET",
        data:{pro_id:pro_id,qtn:quantity},
        datatype: 'string',
        cache: false,
        async: false,
        success:function(result){
          var myObj3 = JSON.parse(result);

          if(myObj3 != null) {
              var last_quantity = myObj3.available_quantity;


              var left1 = last_quantity - quantity;
              var left = left1 - bonus;
             
              if(left<left1){
                alert('quantity not available');
              }
              $("#available_q").val(left);

              if (left > -1) {
                  $('.sale_data_add').removeAttr("disabled");
                  $('#available_q').css("border", "0px solid transparent");

              } else {
                  $('.sale_data_add').prop("disabled", true);
                  $('#available_q').css("border", "1px solid red");
              }
          }
          else
              alert("This product don't have available quantity in stock");

          
          
        },
        error:function(result){
          alert("ERROR > "+result);
        }
    });
}


var $rows = $('#tblSearch tr');
$('.search_available_q').click( function(){
    var string = $('select[name=a_q_s]').val().toLowerCase();

    $rows.show().filter(function() {
        // var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        var text = $(this).text().toLowerCase();
        return !~text.indexOf(string);
    }).hide();

     $rows.first().show();
});

  $('#client').change(function(){
     var client_id = $(this).val();
     if (client_id == -1) {
       $.ajax({
          url:base_url+"/create_urgent_client_view",
          type:"GET",
          success:function(result)
          {
            $('.UrgentClientView').html(result);
            $('#myModal').modal("show");
          },
          error: function(er) {
               alert("ERROR "+er);
           }
         });
     }
   });

   $('#create_urgent_client_post').submit(function(e){

     $('#contact_name').on('input', function() {
       var input=$(this);
       var is_name=input.val();
       if(is_name){input.removeClass("invalid").addClass("valid");}
       else{input.removeClass("valid").addClass("invalid");}
     });

     var formDatas = $("#create_urgent_client_post").serializeArray();

     var allInputFilled = true;

     jQuery.each( formDatas, function( key, field ) {
       if(!field.value){
         allInputFilled = false;
       }
     });

     if(!allInputFilled){
       alert("All Input field need to be filled....");
     }
     else{
       $.ajax({
          url:base_url+"/create_urgent_client_action",
          type:"POST",
          data: $("#create_urgent_client_post").serialize(),
          success:function(clients)
          {
           $('#myModal').modal("hide");
           $('#client').css('display', 'none');
           $('#divForSelectingClient').html(clients);
          },
          error: function(er) {
               alert("ERROR "+er);
           }
         });
     }
     e.preventDefault();
   });

$("a[name=lc_purchase_summary_btn]").on("click", function (ev) {
    ev.preventDefault();
    var lc_purchase_product_id = $(this).attr("data-index");
    $.ajax({
      url:base_url+"/lc_product/purchase/summary",
      type:"GET",
      data:"lc_purchase_product_id="+lc_purchase_product_id,
      success:function(result)
      {
       $('.lc_purchase_product_view_modal').html(result);
       $('#myModal').css("display", "block");
       $('#myModal').modal("show");
      },
      error: function(er) {
           alert("ERROR "+er);
       }
     });
    ev.preventDefault();
});

var acStatus = 0;
$("a[name=lc_purchase_table_update_btn]").on("click", function (ev) {
    ev.preventDefault();
    var lc_purchase_product_id = $(this).attr("data-index");
    $.ajax({
      url:base_url+"/lc_product/purchase/table_update",
      type:"GET",
      data:"lc_purchase_product_id="+lc_purchase_product_id,
      success:function(result)
      {
       // $(".lc_purchase_product_view_modal" ).empty();
       $('.lc_purchase_product_view_modal').html(result);
       $('#myModal').css("display", "block");
       $('#myModal').modal("show");
       acStatus = 1;
      },
      error: function(er) {
           alert("ERROR "+er);
       }
     });
    acStatus =1;
});

// $(document).ready(function(){
// $('#client').change(function(){
//    var client_id = $(this).val();
//    if (client_id == -1) {
//      $('#myModal').css("display", "block");
//      $('#myModal').modal("show");
//    }else{
//     $('#myModal').css("display", "none");
//      $('#myModal').modal("hide");
//    }
//  });
//  });

function printDiv(lukaku){

  var divToPrint=document.getElementById(lukaku);

  var newWin=window.open('','Print-Window');

  newWin.document.open();
newWin.document.write('<html><head><title>' + document.title  + '</title>');
  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
if(lukaku=='page-wrap'){
newWin.document.write('<html><body><link href="'+base_url+'/assets/stylesheets/challan.css" rel="stylesheet" type="text/css" /></body></html>');
}
  newWin.document.close();

  setTimeout(function(){newWin.close();},10);
}

$('#lc_search_with_others_field').click(function(ev) {
    $('#lc_others_seach_field_box').css("display", "block");
    $('#lc_search_with_others_field_close').css("display", "block");
     ev.preventDefault();
})

$('#lc_search_with_others_field_close').click(function(ev) {
    $('#lc_others_seach_field_box').css("display", "none");
    $('#lc_search_with_others_field_close').css("display", "none");
     ev.preventDefault();
})

$('#close_lc_purchase_update_modal').click(function(ev) {
    $('#myModal').css("display", "none");
    $('#myModal').modal("hide");
    location.reload(true);
    ev.preventDefault();

})

$('#update_lc_product_btn').click(function(ev) 
{
  $('#product').prop('disabled', false);
  $('#margin').prop('disabled', false);
  
   $.ajax({
      url:base_url+"/lc_product/purchase/lc_purchase_update_post",
      type:"POST",
      data: $("#lc_purchase_update_form_data").serialize(),
      success:function(result)
      {
        showResultofPurchaseUpdate(result);
         $('#update_lc_product_btn').prop('disabled',true);
         $('#myModal').modal("hide");
         location.reload();
         alert("Update Successful");
      },
      error: function(er) {
           alert("ERROR "+er);
       }
     });
    ev.preventDefault();
})

$('a[name=purchase_product_details_modal_view]').click(function(ev){
  var id = $(this).attr("data-index");
 $.ajax({
    url:base_url+"/product_view_modal",
    type:"GET",
    data:"id="+id,
    success:function(result)
    {
      $('.productViewModal').html(result);
      $('#myModal').modal("show");
    },
    error: function(er) {
         alert("ERROR "+er);
     }
   });
 ev.preventDefault();
});

$('a[name=purchase_product_update_modal_view]').click(function(ev){

  var id = $(this).attr("data-index");
 $.ajax({
    url:base_url+"/product_update_modal",
    type:"GET",
    data:"id="+id,
    success:function(result)
    { 
      $('.productViewModal').html(result);
      $('#myModal').modal("show");
      $("#myModal").addClass('different_input_field');
    },
    error: function(er) {
         alert("ERROR "+er);
     }
   });
 ev.preventDefault();
});

$('#purchase_data_update').click(function(ev) 
{
  $('#product').prop('disabled', false);
   $.ajax({
      url:base_url+"/product/purchase/pro_purchase_update_post",
      type:"POST",
      data: $("#pro_purchase_update_form_data").serialize(),
      success:function(result)
      {
        showResultofPurchaseUpdate(result);
        $('#purchase_data_update').prop('disabled',true);
        //   console.log(result);
      },
      error: function(er) {
           alert("ERROR "+er);
           console.log(er);
       }
     });
  ev.preventDefault();
})


function showResultofPurchaseUpdate(result) 
{
  $('#show_error').css("display", "block");
  $('#show_error').html(result);
}

$('#chalanGenagareBtn').click(function(ev) 
{
 $(".preLoad").css('display','block');

   $.ajax({
      url:base_url+"/chalan/generate",
      type:"POST",
      data: $('#GenerateForm').serialize(),
      success:function(result)
      {

        $(".kol").html(result);
        $(".preLoad").css('display','none');
        $(".kol").css('display','block');

      },
      error: function(er) {
        $(".preLoadImageViewd").css('display','none');
        $(".shobtn").css('display','none');
           alert("ERROR "+er);
       }
     });
    ev.preventDefault();
})

//product purchase table view start 
var prch_arr = [];
$('.purchase_data_add').click(function(ev){
      var product_group = $('select[name=product_group]').find(":selected").text();
      var product_group_id = $('select[name=product_group]').val();      
      var date = $('#date').val();
      var client_name = $('select[name=client]').find(":selected").text();
      var client_id = $('select[name=client]').val();       
      var product_code = $('input[name=product_code]').val();     
      var product_id = $('select[name=product]').find(":selected").val();
      var invoice = $('input[name=invoice]').val();  
      var quantity = $('input[name=quantity]').val();
      var price = $('input[name=price]').val();        
      var bonus = $('input[name=bonus]').val();    
      var due_amount = $('input[name=due_amount]').val();  
      var paid_amount = $('input[name=paid_amount]').val();  
      var chart_of_acc = $('select[name=chart_of_acc]').find(":selected").val();

       if(product_group != '' &&
          product_group_id != '' &&
          date != '' &&
          client_name != '' &&
          client_id != '' &&
          product_id != '' &&
          invoice != '' &&
          quantity != '' &&
          paid_amount != '' &&
          chart_of_acc != '' &&
          price != '')
       {
          prch_frmval(null);
       }
       else{
        var varName = "";
        if(product_group_id == ""){ varName += "Product Group\n" }
        if(date == ""){ varName += "Date\n" }
        if(product_id == ""){ varName += "Product Name\n" }
        if(client_id == ""){ varName += "Client \n" }
        if(invoice == ""){ varName += "Invoice\n" }
        if(quantity == ""){ varName += "Quantity\n" }
        if(price== ""){ varName += "Price\n" }
        if(paid_amount== ""){ varName += "Paid Amount\n" }
        alert(varName+ " \nFill these box.");
      }

    ev.preventDefault();
});

function prch_frmval(ind=null,val){

if(ind!= null){
    prch_arr.splice(ind,1);
    console.log(prch_arr);
    var token = $("input[name = _token]").val();
    var arr_val = JSON.stringify(prch_arr);
  }
   else{
    var values = $("#prch_form").serializeArray();
    var product = $('select[name=product]').find(":selected").text();
    prch_arr.push(values);
    values.push({"name":"product_name","value":product});
    var token = $("input[name = _token]").val();
    var arr_val = JSON.stringify(prch_arr);

}

$.ajax({
        type: "POST",
        url:base_url+"/product/get_from_value",
        datatype:'json',
        cache:false,
        data: {_token: token,prch_val: arr_val},
        success: function (data) {
          $(".view_form").html(data);
          //console.log(data);
        },
        error: function (data) {
         
        }
    }); 

};
//product purchase table view end

 $(document).on('click', '.delete', function() {
  //console.log(prch_arr);
  var val = $(this).val();
  prch_frmval(val);
 
});



 