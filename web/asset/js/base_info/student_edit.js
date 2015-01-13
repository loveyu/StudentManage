/**
 * Created by loveyu on 2015/1/12.
 */
jQuery(function ($) {
	if (typeof city_list != "undefined") {
		var ID_is_province = $("#ID_is_province");
		var ID_is_city = $("#ID_is_city");
		$.each(city_list, function (index, value) {
			ID_is_province.append("<option>" + index + "</option>");
		});
		ID_is_province.change(function () {
			ID_is_city.find(".x").remove();
			$.each(city_list[ID_is_province.val()], function (index, value) {
				ID_is_city.append("<option class='x'>" + value + "</option>");
			});
		});
		ID_is_province.val(edit_info.is_province);
		ID_is_province.trigger('change', edit_info.is_province);
		ID_is_city.val(edit_info.is_city);
	}
});