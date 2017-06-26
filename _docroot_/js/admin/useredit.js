$(document).ready(function(){

/* Activate account till 2050 checkbox */
    $('body').on('click','#checkbox_id',function(){
        if($('#checkbox_id').is(":checked"))
           $(".hide_on_check").hide();
        else
           $(".hide_on_check").show();
    });

/* Show/Hide div on Deactivate select */
    $('body').on('change','.userStatus',function(){
        var status = $('.userStatus').val();
        if(status == "Deactivated"){
            $('.common').hide();
            $("#dynamicunpublishpopup").fadeIn();
        }
        else{
            $('.common').show();
        }
    });

 /* Useredit form submit */
    $('body').on('click','#btnSave',function(){
        $('#userFormEdit').submit();
    });

 /*Calender open when clicked on Set Auto-Renew option */
    $('body').on('click', '#setauto', function () {
       $('.renewDate').css('display','block');
    });

/*Calender closed when clicked on Remove Auto-Renew option */
    $('body').on('click', '#removeauto', function () {
       $('.renewDate').css('display','none');
    });

 /* Validation of (Add New User) User Registration Page */
    $('#regSave').click(function(){
         var email = $('#emailReg').val();
         var password = $('#passReg').val();
         var pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$@!%&*?])[A-Za-z\d#$@!%&*?]{8,}$/;// for password
         var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/; // for email

         if (email == ""){
             $('#errorReg').css('display','block');
             $('#errorReg').html("Please enter an Email");
             $('#errorPass').css('display','none');
             return false;
         }
         else if(!expr.test(email)){ // regex validation for email
             $('#errorReg').css('display','block');
             $('#errorReg').html("Please enter a valid Email");
             $('#errorPass').css('display','none');
             return false;
         }
         else if(password == ""){
             $('#errorPass').css('display','block');
             $('#errorPass').html("Please enter a password");
             $('#errorReg').css('display','none');
             return false;
         }
         else if(!pattern.test(password)){ // regex validation for password
             $('#errorPass').css('display','block');
             $('#errorPass').html("Password should contain minimum 8 characters - at least 1 Uppercase Alphabet, 1 Lowercase Alphabet, 1 Number and 1 Special Character");
             $('#errorReg').css('display','none');
             return false;
         }
         else{

             $.ajax({
                type: "POST",
                url: baseUrl + '/userregistration/saveuser',
                data :{ email : email,password: password},
                success: function(response){
                    if(response.trim()== "error"){
                       $('#errorReg').html("<font color='red'> Invalid! Email already exists </font>");
                             return false; //error message
                         }
                         else{
                              window.location=baseUrl + '/userregistration/userdetails';
                         }
                },
            });

         }
    });

});

 /* Display Selected option in userdetails (start)*/
    $(function () {

		$("#nominee_Cat").change(function(){
			     searchregisterTable($(this).val());
		});
});

function searchregisterTable(inputVal)
{
	$("#userDetails").addClass("show_select");
	var table = $('#userDetails');
	var check = false;
	table.find('tr.show_rows').each(function (index, row)
	{
		$(row).removeClass("for_search");
		var allCells = $(row).find('td');
		var hidden_cnt = $('#hidden_cnt').val();
		var i=0;
		if (allCells.length > 0)
		{
			var found = false;
			allCells.each(function (index, td)
			{
				var regExp = new RegExp(inputVal, 'i');
				if (regExp.test($(td).text()))
				{

					found = true;
					check = true;
					return false;
				}
			});
			if (found == true)
			{
				$('#hidden_tr').hide();
				$(row).show();
				$(row).addClass("for_search");
				$('#userDetails').find("thead tr").addClass("for_search");
				$(".for_title").css("display", "table-row");
			} else
			{
				$(row).hide();
			}
			$(".for_title").css("display", "table-row");
		$('.hidden_head').hide();

		}
	});
	if(check==false)
	{
		$('#hidden_tr').show();
		$('.hidden_head').hide();
        $('.numberclasscheck').text("Number Of User(s) :0"); //when no rows match

	}
	else
	{
		$('#hidden_tr').hide();
		$('.hidden_head').hide();
        if(inputVal != ""){
            var numOfVisibleRows = ($('tr:visible').length) - 1; //when rows match
            $('.numberclasscheck').text("Number Of User(s) :" + numOfVisibleRows); //when rows match
        }
        else{
            var numOfVisibleRows = ($('tr:visible').length) - 1; //when "Show All" selected
            $('.numberclasscheck').text("Number Of User(s) :" + numOfVisibleRows); //when "Show All" selected
        }
	}
}
/*Display selected option (end)*/

/*Popup Appear When clicked on Delete User Icon*/
$(".deleteUser").on('click', function (event)
	{
		 $("#dynamicpagecreatepopup").fadeIn();
		 var abc= $(this).parent().prev().val();
		 $('#hidden_userid').val(abc);
	});

/*Popup Appear When clicked on Restore User Icon*/
$(".restoreUser").on('click', function (event)
	{
		 var deleteId = $(this).parent().prev().val();
		 $('#hidden_userid').val(deleteId);

         $.ajax({
                type: "POST",
                url: baseUrl + '/userregistration/restoreuser',
                data :{ deleteId: deleteId},
                success: function(response){
                    if(response.trim()== "error"){
                      $("#dynamicalertpopup").fadeIn();
                             return false; //error message
                         }
                         else{
                              window.location=baseUrl + '/userregistration/userdetails';
                         }
                },
            });
	});
