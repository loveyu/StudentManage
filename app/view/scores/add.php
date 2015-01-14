<?php
/**
 * @var $this \UView\Scores
 */
$this->get_header(); ?>
	<h1>为专业或班级添加选课</h1>
	<form class="add_form" method="post" action="<?php echo get_url('Scores', 'add_ajax') ?>">
		<div class="form-group">
			<label class="sr-only" for="ID_cio_id">学院</label>

			<div class="input-group">
				<div class="input-group-addon">学院</div>
				<select class="form-control" id="ID_cio_id">
					<option></option>
					<?php echo html_option(get_college_info(), "") ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="sr-only" for="ID_id_year">年级</label>

			<div class="input-group">
				<div class="input-group-addon">年级</div>
				<select class="form-control" id="ID_id_year">
					<option></option>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="sr-only" for="ID_id_id">专业</label>

			<div class="input-group">
				<div class="input-group-addon">专业</div>
				<select class="form-control" name="id_id" id="ID_id_id">
					<option></option>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="sr-only" for="ID_icl_id">班级</label>

			<div class="input-group">
				<div class="input-group-addon">班级(可选)</div>
				<select class="form-control" name="icl_id" id="ID_icl_id">
					<option></option>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="sr-only" for="ID_mc_id">课程</label>

			<div class="input-group">
				<div class="input-group-addon">课程ID</div>
				<input class="form-control" id="ID_mc_id" name="mc_id" type="number"/>
			</div>
		</div>

		<div class="form-group">
			<button type="submit" class="btn btn-primary">提交</button>
		</div>


	</form>
	<script>
		jQuery(function ($) {
			$("form").ajaxForm(function (data) {
				if (data.status) {
					modal_show("添加成功", "<p class='text-success'>添加成功，共添加"+data.msg+"条选课记录</p>");
				} else {
					alert(data.msg);
				}
			})
		});
	</script>
	<script src="<?php echo $this->get_asset("js/scores_add.js") ?>"></script>

<?php
$this->get_footer();