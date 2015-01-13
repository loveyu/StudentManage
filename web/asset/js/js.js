function modal_show(title, content, callback) {
	$("#commonModal").remove();
	$("body").append("<div id='commonModal' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='commonModalMyModalLabel' aria-hidden='true'>" +
		'<div class="modal-dialog">' +
		'<div class="modal-content">' +
		'<div class="modal-header">' +
		'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
		'<h4 class= "modal-title" id="commonModalMyModalLabel" >' + title + '</h4 >' +
		'</div>	' +
		"<div class='modal-body'>" + content + "</div>" +
		'<div class="modal-footer">' +
		'<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>' +
		"</div></div></div></div>"
	);
	if ($.isArray(callback)) {
		for (var i = 0, l = callback.length; i < l; i++) {
			if (callback[i].hasOwnProperty('type') && callback[i].hasOwnProperty('call')) {
				$("#commonModal").on(callback[i]['type'] + ".bs.modal", callback[i]['call']);
			}
		}
	} else {
		if (typeof callback != 'undefined' && callback.hasOwnProperty('type') && callback.hasOwnProperty('call')) {
			$("#commonModal").on(callback['type'] + ".bs.modal", callback['call']);
		}
	}
	$('#commonModal').modal('show');
}
function size_change() {
	var height = $(window).height();
	$("#RightContent").css("min-height", height + "px");
	$("#LeftMenu").css("min-height", height + "px");
	if ($("#RightContent").height() > height) {
		$("#LeftMenu").height($("#RightContent").height());
	}
}
jQuery(function ($) {
	size_change();
	$(window).resize(size_change);
	$("#PageNavChange").change(function () {
		var v = $(this).val();
		var href = location.href;
		if (href.indexOf('?') > 0) {
			location.href = href + "&n=" + v;
		} else {
			location.href = href + "?n=" + v;
		}
	});
});
