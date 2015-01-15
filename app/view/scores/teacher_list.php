<?php
/**
 * @var $this \UView\Scores
 */
$this->get_header(); ?>
	<h1>查询我的课程教学表</h1>
	<form class="form-inline" method="post" action="<?php echo get_url('Scores', 'get_ajax') ?>?type=teacher">
		<div class="form-group">
			<label class="sr-only" for="ID_mc_grade">年级</label>

			<div class="input-group">
				<div class="input-group-addon">年级</div>
				<select name="mc_grade" class="form-control" id="ID_mc_grade">
					<option value="">全部</option>
					<?php echo html_option(array_number_dd(date("Y"), 2001, true), date("Y")-1) ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="sr-only" for="ID_mc_year">学年</label>

			<div class="input-group">
				<div class="input-group-addon">学年</div>
				<select name="mc_year" class="form-control" id="ID_mc_year">
					<option value="">全部</option>
					<?php echo html_option(array_number_dd(date("Y"), 2001, true), date("Y")) ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="sr-only" for="ID_mc_number">学期</label>

			<div class="input-group">
				<div class="input-group-addon">学期</div>
				<select name="mc_number" class="form-control" id="ID_mc_number">
					<option value="">全部</option>
					<?php echo html_option(array_number_aa(1, 5, true), 1) ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">查看</button>
		</div>
	</form>
	<table id="Scores_table" class="table table-striped  table-hover" style="display: none;">
		<thead>
		<tr>
			<th><label class="glyphicon glyphicon-pencil"></label></th>
			<th>课程</th>
			<th>学年</th>
			<th>学期</th>
			<th>学分</th>
			<th>学时</th>
			<th>专业</th>
			<th>年级</th>
			<th>学院</th>
			<th>校区</th>
		</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<script>
		jQuery(function ($) {
			$("form").ajaxForm(function (data) {
				if (data.status) {
					if (data.msg.length == 0) {
						modal_show("无数据", "<p class='text-warning'>未查询到数据</p>")
					} else {
						$("#Scores_table tbody tr").remove();
						var obj = $("#Scores_table tbody");
						for (var i = 0; i < data.msg.length; i++) {
							var x = data.msg[i];
							obj.append("<tr><td><a href='#' class='btn btn-sm btn-primary' onclick='return fs(\""+ x.mc_id+"\")'>登分</a></td><td>["+ x.mc_id+"]" + x.cu_name + "</td><td>"+x.mc_year+"</td><td>"+x.mc_number+"</td>"+
							"<td>" + x.cu_point + "</td><td>" + x.cu_time + "</td>" +
							"<td>" + x.id_name + "</td><td>" + x.mc_grade + "</td>" +
							"<td>" + x.ico_name + "</td><td>" + x.ic_name + "</td></tr>");
						}
						$("#Scores_table").show();
					}
				} else {
					alert(data.msg);
				}
			})
		});
		function fs(id){
			location.href = "<?php echo get_url('Scores','teacher_add_scores')?>?id="+id;
			return false;
		}
	</script>

<?php
$this->get_footer();