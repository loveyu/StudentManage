<?php
/**
 * @var $this \UView\Report
 */
$this->get_header(); ?>
	<h1>成绩报表查询</h1>
	<form class="add_form" method="get" action="<?php echo get_url('Report', 'post') ?>" target="_blank">
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
			<label class="sr-only" for="ID_mc_year">学年</label>

			<div class="input-group">
				<div class="input-group-addon">学年</div>
				<select class="form-control" name="mc_year" id="ID_mc_year">
					<option></option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="sr-only" for="ID_mc_number">学期</label>

			<div class="input-group">
				<div class="input-group-addon">学期</div>
				<select class="form-control" name="mc_number" id="ID_mc_number">
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
			<button type="submit" class="btn btn-primary">提交</button>
		</div>


	</form>
	<script src="<?php echo $this->get_asset("js/report_select.js") ?>"></script>
<?php
$this->get_footer();
