$(document).ready(function ()
{
	$.ajaxSetup(
	{
		dataType:	"json",
		error:		function (c, d, e)
					{
						$("#error").html("Ошибка: " + d + ": " + e);
					},
		type: 		"POST"
	});

	$("form").on("submit", function(a)
	{
		var form = $(this);
		var data = form.serialize();

		$.ajax(
		{
			data:		data,
			success:	function (f)
					{
						$("ul").html("");

						if (f.error != undefined)
						{
							$("#error").html(f.error);
							$("#list").hide();
						}
						else if (f.name_customer == undefined)
						{
							$("#error").html("");
							$("#list").show();

							for (key in f)
							{
								$("ul").append("<li><a href=\"#" + key + "\" data-id=\"" + key + "\">" + f[key] + "</a></li>");
							}
						}
					}
		});

		a.preventDefault();
	});

	$("input").on("change input", function()
	{
		$("#notify").hide();
		$("ul").html("");
		$("#error").html("");
		$("#list").hide();

		var check = 0;

		$("input").each(function ()
		{
			if (($(this).prop("checked") === true) && ($("[name=\"search\"]").val() != "")) check++;
		});

		if (check > 0) $("button").prop("disabled", false);
		else $("button").prop("disabled", true);

		$("td").each(function ()
		{
			var td = $(this);
			if (td.attr("id") != undefined) td.html("");
		});
	});

	$("ul").on("click", "a", function (a)
	{
		var id = $(this).data("id");

		$.ajax(
		{
			data:
					{
						id_contract:	id
					},
			success:	function (f)
					{
						if (f.error != undefined) $("#error").html(f.error);
						else
						{
							$("#error").html("");
							$("#name_customer").html(f.name_customer);
							$("#company").html(f.company);
							$("#number").html(f.number);
							$("#date_sign").html(f.date_sign);
							if (f.title_service != undefined)
							{
								var str = f.title_service;
								str = str.join("<br>");
								$("#services_name").html(str);
							}
							$("#notify").show();
						}
					}
		});

		a.preventDefault();
	});
});