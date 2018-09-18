var anyFieldChange = false;

$("#account_head_name").change(function(){

  var account_head_id = $("#account_head_name").val();

  anyFieldChange = true;

  $("#account_sub_head_name option").remove();

  var myObj = callAjax("/api/accountsSubHead", account_head_id);

  if(myObj != "error")
  {
    appendSelectOption('#account_sub_head_name');

    for(i in myObj){
      $('#account_sub_head_name').append($('<option>', {
          value: myObj[i].accounts_sub_heads_id,
          text : myObj[i].sub_head
      }));
    }
    $('#account_sub_head_name').removeAttr("disabled");
    changeOthersProperties('#account_head_class_name');   
    changeOthersProperties('#account_head_sub_class_name');
  }
  else{
    alert(myObj);
  }
});

$("#account_sub_head_name").change(function(){

  var account_sub_head_id = $("#account_sub_head_name").val();

  $("#account_head_class_name option").remove();

  var myObj = callAjax("/api/accHeadClass", account_sub_head_id);

  if(myObj != "error")
  {
    appendSelectOption('#account_head_class_name');

    for(i in myObj){
          $('#account_head_class_name').append($('<option>', {
              value: myObj[i].mxp_acc_classes_id,
              text : myObj[i].head_class_name
          }));
    }
    $('#account_head_class_name').removeAttr("disabled");
    changeOthersProperties('#account_head_sub_class_name');
  }
  else{
    alert(myObj);
  }
});

$("#account_head_class_name").change(function(){

  var acc_class_id = $("#account_head_class_name").val();

  $("#account_head_sub_class_name option").remove();

  var myObj = callAjax("/api/accSubHeadClass", acc_class_id);

  if(myObj != "error")
  {
    appendSelectOption('#account_head_sub_class_name');

    for(i in myObj){
          $('#account_head_sub_class_name').append($('<option>', {
              value: myObj[i].mxp_acc_head_sub_classes_id,
              text : myObj[i].head_sub_class_name
          }));
    }
    $('#account_head_sub_class_name').removeAttr("disabled");
  }
  else{
    alert(myObj);
  }
});

$('#acc_update_btn').click(function(){ makeEnableOthersField('#acc_update_form select'); });

$('#submitMe').click(function(){ makeEnableOthersField('#acc_create_form select'); });

function makeEnableOthersField(FormName){
  if(!anyFieldChange){
    $(FormName).removeAttr("disabled");
  }  
}

function changeOthersProperties(selectFieldName)
{
  $(selectFieldName +" option").remove();
  appendSelectOption(selectFieldName);
  $(selectFieldName).prop('disabled', true);
}

function callAjax(url, data)
{
  var json;
  $.ajax({
      url:base_url+url,
      type:"GET",
      data:'id='+data,
      datatype: 'json',
      cache: false,
      async: false,
      success:function(result){
        json = result;
      },
      error:function(result){
        json = "error";
      }
    });
  return JSON.parse(json);
}

function appendSelectOption(selectFieldName)
{
  $(selectFieldName).append($('<option>', {
        value: "",
        text : "Select"
    }));
}

