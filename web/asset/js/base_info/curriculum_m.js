/**
 * Created by loveyu on 2015/1/11.
 */
jQuery(function ($) {
	var ID_mc_grade = $("#ID_mc_grade");
	var ID_id_id = $("#ID_id_id");
	var ID_ico_id = $("#ID_ico_id");
	var ID_cu_id = $("#ID_cu_id");
	ID_ico_id.change(function () {
		ID_mc_grade.find(".x").remove();
		ID_id_id.find(".x").remove();
		ID_cu_id.find(".x").remove();
		var id = $(this).val();
		$.get(BASE_URL + "BaseInfo/ajax/college_select_year", {id: id}, function (data) {
			for (var i = 0; i < data.length; i++) {
				ID_mc_grade.append("<option value='" + data[i] + "' class='x'>" + data[i] + "</option>");
			}
		});
		$.get(BASE_URL + "BaseInfo/ajax/college_curriculum", {id: id}, function (data) {
			$.each(data, function (index, value) {
				ID_cu_id.append("<option value='" + index + "' class='x'>" + value + "</option>");
			});
		});

	});
	ID_mc_grade.change(function () {
		var x = $(this).val();
		if (x == "")return;
		ID_id_id.find(".x").remove();
		$.get(BASE_URL + "BaseInfo/ajax/c_and_y_select_id", {id: ID_ico_id.val(), t: x}, function (data) {
			$.each(data, function (index, value) {
				ID_id_id.append("<option value='" + index + "' class='x'>" + value + "</option>");
			});
		});
	});
});