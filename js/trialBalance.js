$('#TrailbalanceForm').submit(function(ev)
{
    $('.preLoadImageView').css('display','block');
    ajaxFunction("/get_trail_balances",$("#TrailbalanceForm").serialize());
    ev.preventDefault();
});

function ajaxFunction(url, data)
{
    $.ajax({
        url: base_url+url,
        type:"POST",
        data: data,
        success:function(trials)
        {
            // alert(trials);
            // console.log(trials);
            // addtrialsRow(trials);
            if (trials != -1)
            {
                if(trials == '')
                    dataNotFoundView();
                else
                    addtrialsRow(trials);
            }
            else
            {
                $('.preLoadImageView').css('display','none');
                alert('All fileds need to be field');
            }
        },
        error:function(er) {
            alert("ERROR "+er);
        }
    });
}

function addtrialsRow(trials)
{
    updateVIewElements();

    var i = 0;
    var j = 0;
    var k = 0;
    var totalCredit = 0;
    var totalDebit = 0;
    var totalSubClassCredit = 0;
    var totalSubClassDebit = 0;
    var subClasstotalCredit = 0;
    var subClasstotalDebit = 0;
    var index = trials['index'];
    var HeadClrs = ["#C0392B", "#9B59B6", "#5499C7","#48C9B0", "#F1C40F", "#52BE80", "#EB984E"];
    var SubHeadClrs = ["#17202A", "#6E2C00", "#34495E","#145A32", "#F39C12", "#2874A6", "#633974", "#CD6155", "#633974", "#117864", "#21618C",];

    console.log(trials);

    while(i < index.length)
    {
        var trialBal = trials['value'][index[i]][index[i+1]][index[i+2]][index[i+3]][index[i+4]];

        var data = '';
        var dataHead = '<td>';
        var dataSubHead = '<td>';
        var dataClass = '<td>';
        var dataSubClass = '<td>';
        var dataChart = '<td>';

        if(trialBal.balance != 0)
        {
            if(index[i+1] != index[i-4])
            {
                j++;
            }

            if(index[i] != index[i-5])
            {
                // addRow('<b>'+trialBal.head_name_type+'</b>', '', '');
                $('#TrailbalanceTableBody').append('<tr class="gnrlLdgrTableChild" style="color: '+SubHeadClrs[j]+'"><td colspan="3"><b>'+trialBal.head_name_type+'</b></td></tr>');
                // dataHead += trialBal.head_name_type;
            }

            if(index[i+1] != index[i-4])
            {
                // addRow('<b>'+trialBal.sub_head+'</b>', '', '');
                $('#TrailbalanceTableBody').append('<tr class="gnrlLdgrTableChild" style="color: '+SubHeadClrs[j]+'"><td colspan="3"><b>'+trialBal.sub_head+'</b></td></tr>');
                // dataSubHead += trialBal.sub_head;
                // console.log(i);
            }

            if(index[i+2] != index[i-3])
            {
                // addRow('<b>'+trialBal.head_class_name+'</b>', '', '');
                $('#TrailbalanceTableBody').append('<tr class="gnrlLdgrTableChild" style="color: '+SubHeadClrs[j]+'"><td colspan="3"><b>'+trialBal.head_class_name+'</b></td></tr>');
                // dataClass += trialBal.head_class_name;
            }

            if(index[i+3] != index[i-2])
            {
                // addRow('<b>'+trialBal.head_sub_class_name+'</b>', '', '');
                $('#TrailbalanceTableBody').append('<tr class="gnrlLdgrTableChild" style="color: '+SubHeadClrs[j]+'"><td colspan="3"><b>'+trialBal.head_sub_class_name+'</b></td></tr>');
                // dataSubClass += trialBal.head_sub_class_name;
            }
            if(trialBal.balance > 0)
            {
                addRow(trialBal.acc_final_name, 0.00, trialBal.balance, SubHeadClrs[j]);
                totalDebit += trialBal.balance;
            }
            else if (trialBal.balance < 0)
            {
                balance = -1*(trialBal.balance);

                addRow(trialBal.acc_final_name, balance, 0.00, SubHeadClrs[j]);
                totalCredit += balance;
            }

            if(trialBal.balance > 0)
                totalSubClassDebit += trialBal.balance;

            else if (trialBal.balance < 0)
                totalSubClassCredit += -1*(trialBal.balance);

            if(((index[i+5] != index[i-1]))/*&&(trialBal.balance != 0) && (i != 0)*/)
            {
                $('#TrailbalanceTableBody').append('<tr class="gnrlLdgrTableChild" style="color: '+SubHeadClrs[j]+'"><td><b>Total('+trialBal.head_sub_class_name+')</b></td><td>'+totalSubClassCredit+'</td><td>'+totalSubClassDebit+'</td></tr>');
                $('#TrailbalanceTableBody').append('<tr class="gnrlLdgrTableChild" style="color: '+SubHeadClrs[j]+'"><td colspan="3"><label> </label></td></tr>');

                totalSubClassCredit = 0;
                totalSubClassDebit = 0;
            }
            // console.log(index[i+4]+' 1st and 2nd '+index[i-2]+' and balance: '+trialBal.balance);
            // if(index[i] != index[i-5] || index[i+1] != index[i-4] || index[i+2] != index[i-3])
            // {
            //     j++;
            // }
        }
        i += 5;
    }

    addRow('<b>Total</b>', '<b>'+totalCredit+'</b>','<b>'+totalDebit+'</b>');
}

function addRow(dataChart, debit, credit, color)
{
    $('#TrailbalanceTableBody').append('<tr class="gnrlLdgrTableChild" style="color: '+color+'"><td>'+dataChart+
        '</td><td>'+debit+
        '</td><td>'+credit+'</td></tr>');
}

function dataNotFoundView()
{
    $('.preLoadImageView').css('display','none');
    $('.TrailbalanceDataView').css('display','none');
    $('.dataNotFoundMessage').css('display','block');
    $('.dataNotFoundMessage').html('<div class="alert alert-danger" role="alert"><li><span>No Data found</span></li><li><span>Please refilled the input flied</span></li></div>');
}

function updateVIewElements()
{
    $('.preLoadImageView').css('display','none');
    $('.dataNotFoundMessage').css('display','none');
    $('.TrailbalanceDataView').css('display','block');
    $('.gnrlLdgrTableChild').remove();
}

