/**
 * Created by loveyu on 2015/1/11.
 */
jQuery(function ($) {
	var year_s = $("#ID_icl_year");
	var id_s = $("#ID_id_id");
	var c_s = $("#ID_ico_id");
	c_s.change(function () {
		year_s.find(".x").remove();
		id_s.find(".x").remove();
		$.get(BASE_URL + "BaseInfo/ajax/college_select_year", {id: $(this).val()}, function (data) {
			for (var i = 0; i < data.length; i++) {
				year_s.append("<option value='" + data[i] + "' class='x'>" + data[i] + "</option>");
			}
		});
	});
	year_s.change(function () {
		var x = $(this).val();
		if (x == "")return;
		id_s.find(".x").remove();
		$.get(BASE_URL + "BaseInfo/ajax/c_and_y_select_id", {id: c_s.val(), t: x}, function (data) {
			$.each(data, function (index, value) {
				id_s.append("<option value='" + index + "' class='x'>" + value + "</option>");
			});
		});
	});
});