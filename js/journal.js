var topFileds = 0;
var onlytopFiled =0;
$(document).ready(function(){
    var fLoad = 0;
    if(fLoad == 0)
    {
        topFileds = $(".all_input_field li:first").clone();
        fLoad++;

    }

});


$(document).on('change', '.company_id', function() {

    var parentLi = $('.all_input_field').find(this).parent().parent().parent().prop('className');
    $.ajax({
        url:base_url+"/get_role_name/"+$(this).val(),
        type:"GET",
        cache: false,
        async: false,
        success:function(result){

            $("."+parentLi+" #journal_post_top select.user_role option").remove();
            var myObj = JSON.parse(result);
            //console.log(myObj);

            $("."+parentLi+" #journal_post_top select.user_role").append($('<option>', {
                value: "",
                text : "Select Role"
            }));

            for(i in myObj){
                $("."+parentLi+" #journal_post_top select.user_role").append($('<option>', {
                    value: myObj[i].id,
                    text : myObj[i].name
                }));
            }

            $("."+parentLi+" #journal_post_top select.user_role").removeAttr("disabled");

        },
        error:function(result){
            alert("Error");
        }
    });
});


$(document).on('change', '.user_role', function() {

    var parentLi = $('.all_input_field').find(this).parent().parent().parent().prop('className');

    $.ajax({
        url:base_url+"/get_user_by_com_id/"+$(this).val(),
        type:"GET",
        cache: false,
        async: false,
        success:function(res){

            $("."+parentLi+" #journal_post_top select.members option").remove();
            var myObj = JSON.parse(res);

            $("."+parentLi+" #journal_post_top select.members").append($('<option>', {
                value: "",
                text : "Select members"
            }));

            for(i in myObj){
                $("."+parentLi+" #journal_post_top select.members").append($('<option>', {
                    value: myObj[i].id,
                    text : myObj[i].first_name
                }));
            }

            $("."+parentLi+" #journal_post_top select.members").removeAttr("disabled");

        },
        error:function(result){
            alert("Error");
        }
    });
});


$(document).on('change', '.company_id_bottom', function() {

    var parentLi = $('.all_input_field').find(this).parent().parent().parent().prop('className');
    $.ajax({
        url:base_url+"/get_role_name/"+$(this).val(),
        type:"GET",
        cache: false,
        async: false,
        success:function(result){

            $("."+parentLi+" #journal_post_bottom select.user_role_bottom option").remove();
            var myObj = JSON.parse(result);

            $("."+parentLi+" #journal_post_bottom select.user_role_bottom").append($('<option>', {
                value: "",
                text : "Select Role"
            }));

            for(i in myObj){
                $("."+parentLi+" #journal_post_bottom select.user_role_bottom").append($('<option>', {
                    value: myObj[i].id,
                    text : myObj[i].name
                }));
            }

            $("."+parentLi+" #journal_post_bottom select.user_role_bottom").removeAttr("disabled");

        },
        error:function(result){
            alert("Error");
        }
    });
});


$(document).on('change', '.user_role_bottom', function() {

    var parentLi = $('.all_input_field').find(this).parent().parent().parent().prop('className');
    $.ajax({
        url:base_url+"/get_user_by_com_id/"+$(this).val(),
        type:"GET",
        cache: false,
        async: false,
        success:function(res){

            $("."+parentLi+" #journal_post_bottom select.members_bottom option").remove();
            var myObj = JSON.parse(res);

            $("."+parentLi+" #journal_post_bottom select.members_bottom").append($('<option>', {
                value: "",
                text : "Select members"
            }));

            for(i in myObj){
                $("."+parentLi+" #journal_post_bottom select.members_bottom").append($('<option>', {
                    value: myObj[i].id,
                    text : myObj[i].first_name
                }));
            }

            $("."+parentLi+" #journal_post_bottom select.members_bottom").removeAttr("disabled");

        },
        error:function(result){
            alert("Error");
        }
    });
});

//set voucher code
var parentClass = '';
$(document).on('click', '.user_role', function() {
    parentClass = $('.all_input_field').find(this).parent().parent().parent().prop('className');
    $("."+parentClass+" #journal_post_top").click(function(key){
        if($.trim($("."+parentClass+" #journal_post_top select.company_id").val().length) > 0 && $.trim($("."+parentClass+" #journal_post_top input.journal_date").val().length) > 0){
            getLastVoucherId();
        }
    });
});

function setVoucherCode(lastVoucherId){
    var date = $("."+parentClass+" #journal_post_top input.journal_date").val().replace('-', '');
    date = date.replace('-', '');
    date = date.replace('20', '');
    lastVoucherId++;
    var com = jQuery.trim($("."+parentClass+" #journal_post_top select.company_id").find(':selected').text()).substring(0, 3);
    $("."+parentClass+" #journal_post_top input.voucher_num").val(com.toUpperCase()+date+lastVoucherId);
}


$(document).on('click', '.user_role_bottom', function() {
    $("#journal_post_bottom").click(function(key){
        if($.trim($("#journal_post_bottom select.company_id_bottom").val().length) > 0 && $.trim($("#journal_post_bottom input.journal_date_bottom").val().length) > 0){
            getLastVoucherId();
        }
    });
});

function setVoucherCodeBottom(lastVoucherId){
    var date = $("#journal_post_bottom input.journal_date_bottom").val().replace('-', '');
    date = date.replace('-', '');
    date = date.replace('20', '');
    lastVoucherId++;
    var com = jQuery.trim($("#journal_post_bottom select.company_id_bottom").find(':selected').text()).substring(0, 3);
    $("#journal_post_bottom input.voucher_num_bottom").val(com.toUpperCase()+date+lastVoucherId);
}


function getLastVoucherId(){
    $.ajax({
        url:base_url+"/get_last_voucher_id",
        type:"GET",
        cache:false,
        async:true,
        success:function(result){
            setVoucherCode(result),
                setVoucherCodeBottom(result);
        },
        error:function(error){
            alert("Error: "+error);
        }
    });
}
//set voucher code end

$(document).on('change','.transaction_type',function(){
    var transaction_type = $(".all_input_li_1 .transaction_type").find(":selected").val();
    if(transaction_type == 1)
    {
        $("select.transaction_type_bottom").val("2");

    }
    else
    {

        $("select.transaction_type_bottom").val("1");
    }

});

$(document).on('change','.transaction_type_bottom',function(){
    var transaction_type_bottom = $(".transaction_type_bottom").find(":selected").val();
    if(transaction_type_bottom == 1)
    {
        $(".all_input_li_1 select.transaction_type").val("2");
    }
    else
    {

        $(".all_input_li_1 select.transaction_type").val("1");
    }
});



$(document).on('click', '.char_of_acc', function(){
    var char_of_acc_clsnam = $('.all_input_field').find(this).parent().parent().parent().prop('className');
    var char_of_acc_clsnam = "."+char_of_acc_clsnam+" ";
    var char_of_acc_text = $.trim($(char_of_acc_clsnam+" .char_of_acc").find(":selected").text());
    $(char_of_acc_clsnam+" .particulars").val(char_of_acc_text);
});

$(document).on('click', '.char_of_acc_bottom', function(){
    var char_of_acc_text_btm = $(".char_of_acc_bottom").find(":selected").text();
    $(".particulars_bottom").val(char_of_acc_text_btm);

});


// var incre = 2;
// $('#journal_post_add_row').on('click', function(e){

// 	e.preventDefault();
// 	topFileds.clone().addClass('all_input_li_'+incre).removeClass('all_input_li_1').appendTo('.all_input_field');
// 	incre++;

// });

var incre = 2;

function validation(usa){

    var val = 0;

    for (var i = 1; i < usa; i++) {

        var company_id = $(".all_input_li_"+i+" .company_id").find(":selected").val();

        var user_role = $(".all_input_li_"+i+" .user_role").find(":selected").text();

        var amount =  $(".all_input_li_"+i+" .amount").val();

        var journal_date =  $(".all_input_li_"+i+" .journal_date").val();

        var members =   $(".all_input_li_"+i+" .members").find(":selected").text();

        var transaction_type = $(".all_input_li_"+i+" .transaction_type").find(":selected").val();

        var char_of_acc =  $(".all_input_li_"+i+" .char_of_acc").find(":selected").text();

        var voucher_num =  $(".all_input_li_"+i+" .voucher_num").val();

        var cf_code =  $(".all_input_li_"+i+" .cf_code").val();


        var company_id_bottom =$(".company_id_bottom").find(":selected").val();

        var user_role_bottom = $(".user_role_bottom").find(":selected").text();

        var amount_bottom =  $(".amount_bottom").val();

        var journal_date_bottom =  $(".journal_date_bottom").val();

        var members_bottom =   $(".members_bottom").find(":selected").text();

        var transaction_type_bottom = $(".transaction_type_bottom").find(":selected").val();

        var char_of_acc_bottom=  $(".char_of_acc_bottom").find(":selected").text();

        var voucher_num_bottom =  $(".voucher_num_bottom").val();

        var cf_code_bottom =  $(".cf_code_bottom").val();



        if (company_id != "" &&
            user_role != "" &&
            journal_date !="" &&
            char_of_acc !="" &&
            cf_code !="" &&
            amount !="" &&
            members !="" &&
            transaction_type !="" &&
            company_id_bottom !="" &&
            user_role_bottom !=""  &&
            journal_date_bottom !="" &&
            char_of_acc_bottom !="" &&
            cf_code_bottom !="" &&
            amount_bottom !="" &&
            members_bottom !="" &&
            transaction_type_bottom !=""
        )
        {
            val = 1;
        }

        else{
            var varName = "";
            if(company_id == ""){ varName += "Company\n" }
            if(user_role == ""){ varName += "User Role\n" }
            if(journal_date == ""){ varName += "Date\n" }
            if(char_of_acc == ""){ varName += "Chart Of Acc\n" }
            if(amount == ""){ varName += "Amount\n" }
            if(cf_code == ""){ varName += "CF Code\n" }
            if(members == ""){ varName += "Members\n" }
            if(transaction_type == 0){ varName += "Transaction_type\n" }
            if(company_id_bottom== ""){ varName += "Company Bottom\n" }
            if(user_role_bottom == ""){ varName += "User Role Bottom \n" }
            if(journal_date_bottom == ""){ varName += "Date Bottom\n" }
            if(char_of_acc_bottom == ""){ varName += "Char of Acc Bottom\n" }
            if(amount_bottom == ""){ varName += "Amount Bottom\n" }
            if(cf_code_bottom == ""){ varName += "Cf Code Bottom\n" }
            if(members_bottom == ""){ varName += "Members Bottom\n" }
            if(transaction_type_bottom == 0){ varName += "Transaction_type Bottom\n" }
            alert(varName+ " \nFill these box.");
            val = -1;
            break;
        }
    }
    return val;
}

$('#journal_post_add_row').on('click', function(ev) {
    ev.preventDefault();
    var val = validation(incre);
    if( val == 1){
        topFileds.clone().addClass('all_input_li_'+incre).removeClass('all_input_li_1').appendTo(".all_input_field");
        incre++;
    }

});

$('#journal_delete_row').on('click', function() {
    if(incre > 2)
    {
        $('.all_input_li_'+(incre-1)).remove();
        incre--;
    }
    return false; //prevent form submission
});

$('#journal_post_form_id').submit(function (e) {
    e.preventDefault();

    var values = $('#journal_post_form_id').serializeArray();
    $.ajax({
        url:base_url+"/journal/posting/form/upload",
        type: "POST",
        data: values,
        success:function(res){
            $(".alert-danger").css('display','block');
            if($.isEmptyObject(res.error)){
                alert(res.success);
                location.reload();
            }
            else{
                printErrorMsg(res.error);
            }
        }
    });

    function printErrorMsg(res){
        $(".alert-danger").find("ul").html('');
        $(".alert-danger").css('display','block');
        $.each( res, function( key, value ) {
            $(".alert-danger").find("ul").append('<li>'+value+'</li>');

        });
    }

});


