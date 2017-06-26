/*
 * @Author: Rituparna
 * @Date:   2017-02-8 17:46:35
 * @Last Modified by:   Rituparna
 * @Last Modified time: 2017-04-24 18:52:26
 */
/*jslint browser: true*/
/*global $, jQuery, alert*/
/*jslint plusplus: true */
/*jshint -W065 */
/*global baseUrl*/
$(document).ready(function () {
    "use strict";

    /*Dropdown menu on migrate template icon click*/
    $('body').on('click', '.migrateTemplate', function () {
        var dataid = $(this).data("id");
        $('.dropdown' + dataid).slideToggle();
    });

    /*Validation for Tag Edit page*/
    $('body').on('click', '#tagBtnSave', function () {
        var tagName = $('#tagName').val(),
            tagDesc = $.trim($('#tagDesc').val()),
            replaceCode = $.trim($('#replaceCode').val());
        if (tagName === "") {
            $('#tagDescError,#replaceCodeError').css('display', 'none');
            $('#tagNameError').css('display', 'block');
            $('#tagNameError').html("<font color='red'> Please enter the tag name </font>");
            return false;
        } else if (tagDesc === "") {
            $('#tagNameError,#replaceCodeError').css('display', 'none');
            $('#tagDescError').css('display', 'block');
            $('#tagDescError').html("<font color='red'> Please enter the tag description </font>");
            return false;
        } else if (replaceCode === "") {
            $('#tagNameError,#tagDescError').css('display', 'none');
            $('#replaceCodeError').css('display', 'block');
            $('#replaceCodeError').html("<font color='red'> Please enter the replace code </font>");
            return false;
        } else {
            $('#tagFormEdit').submit();
            return true;
        }
    });

    /*Blank Validation for Add New Tag page*/
    $('body').on('click', '#newtagSave', function () {
        var newtagName = $('#newtagName').val(),
            newtagDesc = $('#newtagDesc').val(),
            newrepCode = $('#newrepCode').val();

        if (newtagName === "") {
            $('#errornewDesc,#errornewCode').css('display', 'none');
            $('#errornewName').css('display', 'block');
            $('#errornewName').html("<font color='red'> Please enter the tag name </font>");
            return false;
        } else if (newtagDesc === "") {
            $('#errornewName,#errornewCode').css('display', 'none');
            $('#errornewDesc').css('display', 'block');
            $('#errornewDesc').html("<font color='red'> Please enter the tag description </font>");
            return false;
        } else if (newrepCode === "") {
            $('#errornewName,#errornewDesc').css('display', 'none');
            $('#errornewCode').css('display', 'block');
            $('#errornewCode').html("<font color='red'> Please enter the replace code </font>");
            return false;
        } else {

            $.ajax({
                type: "POST",
                url: baseUrl + '/tag/savetag',
                data: {
                    newtagName: newtagName,
                    newtagDesc: newtagDesc,
                    newrepCode: newrepCode
                },
                success: function (response) {
                    if (response.trim() === "error") {
                        $('#errornewDesc,#errornewCode').css('display', 'none');
                        $('#errornewName').css('display', 'block');
                        $('#errornewName').html("<font color='red'> Invalid! Tag name already exists </font>");
                        return false; //error message
                    } else {
                        window.location = baseUrl + '/tag/viewtag';
                    }
                }
            });

        }
    });

    /*Popup Appear When clicked on Delete User Icon*/
    $(".deleteTag").on('click', function (event) {
        $("#dynamicpagecreatepopup").fadeIn();
        var abc = $(this).parent().prev().val();
        $('#hidden_userid').val(abc);
    });


});
