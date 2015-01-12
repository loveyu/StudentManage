<?php
/**
 * @var $this   \UView\BaseInfo
 * @var $__info array
 * @var $__type string
 */
$this->get_header();
$i = 1;
$row = isset($__info['row']) ? $__info['row'] : 0;
?>
	<h3>添加<?php echo $__info['name'] ?></h3>
	<div<?php echo ($row > 0) ? "" : " class=\"add_form\""; ?>>
		<form action="<?php echo get_url('BaseInfo', 'add', $__type) ?>" method="post">
			<?php foreach($__info['filed'] as $name => $v): if(isset($v['hide']) && $v['hide']){
				continue;

			}
				if($row > 0 && (($i - 1) % $row) === 0){
					echo "<div class='row'>";
				} ?>
				<div class="form-group<?php echo ($row > 0) ? (" col-md-" . (12 / $row)) : "" ?>">
					<label class="control-label" for="ID_<?php echo $name ?>"><?php echo $v['name'] ?></label>
					<?php switch($v['type']){
						case "text":
							?>
							<input class="form-control" type="<?php echo isset($v['vt']) ? $v['vt'] : "text"?>" name="<?php echo $name?>"
								   value="<?php echo isset($v['def']) ? $v['def'] : ""?>" id="ID_<?php echo $name ?>">
							<?php break;
						case "select": ?>
							<select class="form-control" id="ID_<?php echo $name ?>" name="<?php echo $name ?>">
								<option value="">--下拉选择--</option>
								<?php echo html_option(isset($v['select_func']) ? call_user_func($v['select_func']) : (isset($v['select_list']) ? $v['select_list'] : []), "") ?>
							</select>
							<?php
							break;
						case "textarea": ?>
							<textarea class="form-control" id="ID_<?php echo $name ?>" name="<?php echo $name ?>"></textarea>
							<?php
							break;
						case "radio":
							echo " : &nbsp;&nbsp;";
							foreach($v['radio'] as $rn => $rv):?>
								<label><input value="<?php echo $rn?>" type='radio' name='<?php echo $name ?>'> <?php echo $rv?>
								</label>&nbsp;&nbsp;&nbsp;
							<?php
							endforeach;
							break;
					} ?>
				</div>
				<?php
				if($row > 0 && ($i % $row) === 0){
					echo "</div>";
				}
				$i++;
			endforeach; ?>
			<?php if($row > 0 && (($i - 1) % $row) !== 0){
				echo "</div>";
			} ?>
			<div class="form-group">
				<button class="btn btn-default" type="submit">添加</button>
			</div>
		</form>
	</div>
<?php if(isset($__info['ajax'])):
	if(is_string($__info['ajax'])):?>
		<script src="<?php echo $this->get_asset("js/base_info/" . $__info['ajax'] . ".js") ?>"></script>
	<?php elseif(is_array($__info['ajax'])): foreach($__info['ajax'] as $js): ?>
		<script src="<?php echo $this->get_asset("js/base_info/" . $js . ".js") ?>"></script>
	<?php
	endforeach;
	endif;endif; ?>
	<script>
		jQuery(function ($) {
			$("form").ajaxForm(function (data) {
				if (data.status) {
					modal_show("添加成功", "<p class='text-success'>添加成功</p>");
				} else {
					alert(data.msg);
				}
			})
		});
	</script>
<?php
$this->get_footer();