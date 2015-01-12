/**
 * Created by loveyu on 2015/1/12.
 */
jQuery(function ($) {
	var ID_ic_name = $("#ID_ic_name");
	var ID_ico_id = $("#ID_ico_id");
	var ID_is_grade = $("#ID_is_grade");
	var ID_id_id = $("#ID_id_id");
	var ID_icl_id = $("#ID_icl_id");
	ID_ic_name.change(function () {
		ID_ico_id.find(".x").remove();
		ID_is_grade.find(".x").remove();
		ID_id_id.find(".x").remove();
		ID_icl_id.find(".x").remove();
		$.get(BASE_URL + "BaseInfo/ajax/campus_select_college", {id: $(this).val()}, function (data) {
			$.each(data, function (index, value) {
				ID_ico_id.append("<option value='" + index + "' class='x'>" + (value) + "</option>");
			});
		});
	});
	ID_ico_id.change(function () {
		ID_is_grade.find(".x").remove();
		ID_id_id.find(".x").remove();
		ID_icl_id.find(".x").remove();
		$.get(BASE_URL + "BaseInfo/ajax/college_select_year", {id: $(this).val()}, function (data) {
			for (var i = 0; i < data.length; i++) {
				ID_is_grade.append("<option value='" + data[i] + "' class='x'>" + data[i] + "</option>");
			}
		});
	});
	ID_is_grade.change(function () {
		var x = $(this).val();
		if (x == "")return;
		ID_id_id.find(".x").remove();
		ID_icl_id.find(".x").remove();
		$.get(BASE_URL + "BaseInfo/ajax/c_and_y_select_id", {id: ID_ico_id.val(), t: x}, function (data) {
			$.each(data, function (index, value) {
				ID_id_id.append("<option value='" + index + "' class='x'>" + value + "</option>");
			});
		});
	});
	ID_id_id.change(function () {
		var x = $(this).val();
		if (x == "")return;
		ID_icl_id.find(".x").remove();
		$.get(BASE_URL + "BaseInfo/ajax/id_select_class", {id: x}, function (data) {
			$.each(data, function (index, value) {
				ID_icl_id.append("<option value='" + index + "' class='x'>" + value + "</option>");
			});
		});
	});
});