<?php
/**
 * @var $this   \UView\BaseInfo
 * @var $__info array
 * @var $__type string
 * @var $__data string
 */
$this->get_header();
if(!is_array($__data)){
	echo "<h3 class='text-danger'>无数据可供编辑</h3>";
} else{
	$i = 1;
	$row = isset($__info['row']) ? $__info['row'] : 0;
	?>
	<h3>编辑<?php echo $__info['name'] ?></h3>
	<div<?php echo ($row > 0) ? "" : " class=\"add_form\""; ?>>
		<form action="<?php echo get_url('BaseInfo', 'edit', $__type) ?>" method="post">
			<?php foreach($__info['filed'] as $name => $v):
				if(isset($v['pk'])){
					$filed_status = " readonly ";
				} else if(!isset($v['edit']) || $v['edit'] == 0){
					$filed_status = " disabled ";
				} else{
					$filed_status = "";
				}
				if($row > 0 && (($i - 1) % $row) === 0){
					echo "<div class='row'>";
				} ?>
				<div class="form-group<?php echo ($row > 0) ? (" col-md-" . (12 / $row)) : "" ?>">
					<label class="control-label" for="ID_<?php echo $name ?>"><?php echo $v['name'] ?></label>
					<?php
					if(isset($v['edit_type'])){
						$v['type'] = $v['edit_type'];
					}
					$show_value = isset($__data[$name]) ? $__data[$name] : "";
					if(isset($v['edit_value_call'])){
						$show_value = $v['edit_value_call']($show_value);
					}
					switch($v['type']){
						case "text":
							?>
							<input <?php echo $filed_status;?>class="form-control" type="<?php echo isset($v['vt']) ? $v['vt'] : "text"?>"
								   name="<?php echo $name?>"
								   value="<?php echo $show_value?>" id="ID_<?php echo $name ?>">
							<?php break;
						case "select": ?>
							<select <?php echo $filed_status; ?> class="form-control" id="ID_<?php echo $name ?>" name="<?php echo $name ?>">
								<option value="">--下拉选择--</option>
								<?php echo html_option(isset($v['select_func']) ? call_user_func($v['select_func']) : (isset($v['select_list']) ? $v['select_list'] : []), $show_value) ?>
							</select>
							<?php
							break;
						case "textarea": ?>
							<textarea <?php echo $filed_status; ?> class="form-control" id="ID_<?php echo $name ?>"
																   name="<?php echo $name ?>"><?php echo htmlentities($show_value) ?></textarea>
							<?php
							break;
						case "radio":
							echo " : &nbsp;&nbsp;";
							foreach($v['radio'] as $rn => $rv):
								$selected = $rn == $show_value ? " checked " : "";
								?>
								<label><input<?php echo $filed_status, $selected;?> value="<?php echo $rn?>" type='radio'
																					name='<?php echo $name ?>'> <?php echo $rv?>
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
				<button class="btn btn-default" type="submit">更新</button>
			</div>
		</form>
	</div>
	<?php if(isset($__info['edit_ajax'])):
		if(is_string($__info['edit_ajax'])){
			$__info['edit_ajax'] = [$__info['edit_ajax']];
		}
		foreach($__info['edit_ajax'] as $js): ?>
			<script src="<?php echo $this->get_asset("js/base_info/" . $js . ".js") ?>"></script>
		<?php
		endforeach;
	endif; ?>
	<script>
		var edit_info = <?php echo json_encode($__data,JSON_UNESCAPED_UNICODE)?>;
		jQuery(function ($) {
			$("form").ajaxForm(function (data) {
				if (data.status) {
					modal_show("编辑成功", "<p class='text-success'>编辑成功</p>");
				} else {
					alert(data.msg);
				}
			})
		});
	</script>
<?php
}
$this->get_footer();