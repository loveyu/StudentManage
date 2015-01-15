<?php
/**
 * @var $this  \UView\Scores
 * @var $__msg string
 */
$this->get_header();
?>
	<h1>输入你的专业选课号</h1>
<?php if(!empty($__msg)){
	echo "<p class='text-danger'>", $__msg, "</p>";
} ?>
	<form action="" method="get" class="add_form">
		<div class="form-group">
			<label class="sr-only" for="ID_mc_id">专业选课号</label>

			<div class="input-group">
				<div class="input-group-addon">专业选课号</div>
				<input name="id" class="form-control" value="<?php echo htmlspecialchars(isset($_GET['id']) ? $_GET['id'] : "") ?>" id="ID_mc_id"/>
			</div>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary">确定</button>
		</div>
	</form>
<?php
$this->get_footer();