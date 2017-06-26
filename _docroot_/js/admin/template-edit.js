/* Preview Image before Upload */
window.onload = function () {
     var pathname = window.location.pathname;
     var parts = pathname.split("/");
     var part3 = parts[3];
     if (pathname == '/template/templateedit/'+ part3) {
	 var fileUpload = document.getElementById("fileupload");
	 //var filevalue = fileUpload.value;
	 //var ext = filevalue.split('.').pop().toLowerCase();
	 
	  //alert(ext);
		fileUpload.onchange = function preview(e) {
                var maxfilesize = 1024 * 1024; // 1MB

                if (typeof (FileReader) != "undefined") {
                    var dvPreview = document.getElementById("upload_prev");
                    dvPreview.innerHTML = "";
                    for (var i = 0; i < fileUpload.files.length; i++) {
                        var file = fileUpload.files[i];
			var ext = file.name.split('.').pop().toLowerCase();
                        var size = file.size;
			if (ext == "jpg" || ext == "jpeg" || ext == "gif" || ext == "png" || ext == "bmp") {
                            if(size < maxfilesize){
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                var img = document.createElement("IMG");
                                img.height = "100";
                                img.width = "100";
                                img.src = e.target.result;
                                img.style.marginRight = "12em";
                                img.style.marginTop = "5px";
                                dvPreview.appendChild(img);
                            }
                            reader.readAsDataURL(file);
                        }
                        }else {
							$("#dynamicpagecreatepopupnewsbrief").fadeIn();
							$('#hidden_newsbrief').html(file.name + " is not a valid image file.");
                            dvPreview.innerHTML = "";
                            return false;
                        }
                    }
                } else {
					$("#dynamicpagecreatepopupnewsbrief").fadeIn();
					$('#hidden_newsbrief').html("This browser does not support HTML5 FileReader.");
                }
        }
     }
};

/*Popup Appear when clicked on Delete Template Icon*/
$(".delTemplate").on('click', function (event)
	{
		 $("#dynamicpagecreatepopup").fadeIn();
		 var abc= $(this).parent().prev().val();
		 $('#hidden_userid').val(abc);
	});

/*Popup Appear when clicked on Hide button*/
$(".temp_hide").on('click', function (event) {
    var tempid = $(this).parent().prev().val(); // template id
    $('#hidden_tempid_hide').val(tempid); // send template id to popup
    var dataid = $(this).data("id");
    $('#hidden_dataid_hide').val(dataid);
		 $("#dynamichidecreatepopup").fadeIn();
	});

/*Popup Appear when clicked on Show button*/
$(".temp_show").on('click', function (event) {
    var tempid = $(this).parent().prev().val(); // template id
    $('#hidden_tempid_show').val(tempid); // send template id to popup
    var dataid = $(this).data("id");
    $('#hidden_dataid_show').val(dataid);
		 $("#dynamicshowcreatepopup").fadeIn();
	});

$(document).ready(function(){

    /* Hide Template in Gallery(Front) */
    $('body').on('click','.hideTemplate',function(){
        var tempId = $('#hidden_tempid_hide').val(); //template id
        var id = $('#hidden_dataid_hide').val();
        $('#dynamichidecreatepopup').fadeOut();
        $('.tmpImg'+id).parent().siblings('.temp').children('.temp_show').css('display','inline'); //display show btn
        $('.tmpImg'+id).parent().siblings('.temp').children('.temp_hide').css('display','none');
        $.ajax({
                 type : 'POST',
                  url : baseUrl + '/template/hidetemplate',
                 data : {
                           tempId : tempId
                        }
        });

    });

    /* Show Template in Gallery(Front) */
    $('body').on('click','.showTemplate',function(){
         var tempId = $('#hidden_tempid_show').val(); //template id
         var id = $('#hidden_dataid_show').val();
         $('#dynamicshowcreatepopup').fadeOut();
        $('.tmpImg'+id).parent().siblings('.temp').children('.temp_hide').css('display','inline');//display hide btn
        $('.tmpImg'+id).parent().siblings('.temp').children('.temp_show').css('display','none');
         $.ajax({
                 type : 'POST',
                  url : baseUrl + '/template/showtemplate',
                 data : {
                           tempId : tempId
                        }
        });

    });

    /*Template-edit Image Validation(onchange)*/
    $('#fileupload').bind('change', function() {
        var ext = $('#fileupload').val().split('.').pop().toLowerCase();
        var picsize = (this.files[0].size);
        if ($.inArray(ext, ['gif','png','jpg','jpeg']) == -1){
              $('#errorRate,#errorLink,#errorName,#errorImg3,#errorImg2,#upload_prev').css('display','none'); // hides image along with other error messages
              $('#errorImg1').css('display','block');
              $('#errorImg1').html("<font color='red'> Invalid Image Format! Image Format Must Be JPG, JPEG, PNG or GIF </font>");
              $('#btnSave').attr("disabled", true);
              return false;
        }else if(picsize > 1024000){
               $('#errorRate,#errorLink,#errorName,#errorImg1,#errorImg3,#upload_prev').css('display','none'); // hides image along with other error messages
               $('#errorImg2').css('display','block');
               $('#errorImg2').html("<font color='red'> Invalid Image Format! Maximum File Size Limit is 1MB </font>");
               ($('.upload_prev').children('img').attr('src')) == "";
               $('#btnSave').attr("disabled", true);
               return false;
           }
           else{
               $('#errorImg1').css('display','none');
               $('#errorImg2').css('display','none');
               $('#upload_prev').css('display','block');
               $('#btnSave').attr("disabled", false);

          }
    });

    /* Template edit validation */
    $('#btnSave').on("click",function(){
        var tempId = $('#tempId').val();
        var tempName = $('#tempName').val();
        var conversionRate = $('#conversionRate').val();
        var publishLink = $('#publishLink').val();


        if(tempName == ''){ //template name field
            $('#errorRate,#errorLink,#errorImg3,#errorImg1,#errorImg2').css('display','none');
            $('#errorName').css('display','block');
            $('#errorName').html("<font color='red'> Please enter the template name </font>");
              return false;
        }
        else{
            $("#templateEdit").ajaxSubmit({
            data: {
                tempId: tempId,
                       tempName : tempName,
                       conversionRate: conversionRate,
                       publishLink: publishLink,
                       fileupload : 'fileupload'
            },
            success: function (response) {
                if(response.trim()== "error"){
                       $('#errorName').html("<font color='red'> Invalid! Template name already exists </font>");
                             return false; //error message
                         }
                         else{
                              $('#errorName').css('display','none');
                              window.location=baseUrl + '/template/templateview';
                         }
            },
        });

        }
    });


});
