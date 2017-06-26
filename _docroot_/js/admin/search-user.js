
$(function ()
{

		$('#registeredtableSearch').keyup(function ()
		{
			searchregisterTable($(this).val());
		});


});

function searchregisterTable(inputVal)
{
	$("#userDetails").addClass("new_new");
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

	}
	else
	{
		$('#hidden_tr').hide();
		$('.hidden_head').hide();
	}
}
