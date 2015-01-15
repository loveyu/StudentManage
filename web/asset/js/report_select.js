/**
 * Created by loveyu on 2015/1/11.
 */
jQuery(function ($) {
	var ID_cio_id = $("#ID_cio_id");
	var ID_id_year = $("#ID_id_year");
	var ID_id_id = $("#ID_id_id");
	var ID_icl_id = $("#ID_icl_id");
	var ID_mc_year = $("#ID_mc_year");
	var ID_mc_number = $("#ID_mc_number");
	ID_cio_id.change(function () {
		ID_id_year.find(".x").remove();
		ID_id_id.find(".x").remove();
		ID_icl_id.find(".x").remove();
		ID_mc_year.find(".x").remove();
		ID_mc_number.find(".x").remove();
		$.get(BASE_URL + "BaseInfo/ajax/college_select_year", {id: $(this).val()}, function (data) {
			for (var i = 0; i < data.length; i++) {
				ID_id_year.append("<option value='" + data[i] + "' class='x'>" + data[i] + "</option>");
			}
		});
	});
	ID_id_year.change(function () {
		var x = $(this).val();
		if (x == "")return;
		ID_id_id.find(".x").remove();
		ID_icl_id.find(".x").remove();
		ID_mc_year.find(".x").remove();
		ID_mc_number.find(".x").remove();
		$.get(BASE_URL + "BaseInfo/ajax/c_and_y_select_id", {id: ID_cio_id.val(), t: x}, function (data) {
			$.each(data, function (index, value) {
				ID_id_id.append("<option value='" + index + "' class='x'>" + value + " - " + index + "</option>");
			});
		});
	});

	ID_id_id.change(function () {
		var x = $(this).val();
		if (x == "")return;
		ID_icl_id.find(".x").remove();
		ID_mc_year.find(".x").remove();
		ID_mc_number.find(".x").remove();
		$.get(BASE_URL + "BaseInfo/ajax/id_select_class", {id: x}, function (data) {
			$.each(data, function (index, value) {
				ID_icl_id.append("<option value='" + index + "' class='x'>" + value + " - " + index + "</option>");
			});
		});
		$.get(BASE_URL + "BaseInfo/ajax/mc_select_year_by_id", {id: x}, function (data) {
			$.each(data, function (index, value) {
				ID_mc_year.append("<option value='" + index + "' class='x'>" + value + "</option>");
			});
		});
	});
	ID_mc_year.change(function(){
		var x = $(this).val();
		if (x == "")return;
		ID_mc_number.find(".x").remove();
		$.get(BASE_URL + "BaseInfo/ajax/mc_select_number_by_id_year", {id: ID_id_id.val(),t:x}, function (data) {
			$.each(data, function (index, value) {
				ID_mc_number.append("<option value='" + index + "' class='x'>" + value + "</option>");
			});
		});
	});
});