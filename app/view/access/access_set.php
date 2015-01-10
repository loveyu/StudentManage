<?php
/**
 * @var $this          \UView\Access
 * @var $__role        array
 * @var $__role_access array
 * @var $__permission  array
 */
$this->get_header();
$no_list = array_diff(array_keys($__permission), array_keys($__role_access));
?>
	<h3>权限设置</h3>
	<p>角色：<span class="text-success"><?php echo $__role['r_name'] ?></span>(<?php echo role_status($__role['r_status']) ?>)</p>
	<h4>已有权限</h4>
<?php if(empty($__role_access)): ?>
	<p class="bg-danger padding10">无权限</p>
<?php else:foreach($__role_access as $key => $v): ?>
	<button class="btn btn-danger"
			onclick="access_del(<?php echo $key ?>)"><?php echo $__permission[$key] ?>(<?php echo access_status($v) ?>)
	</button>
<?php endforeach; endif; ?>
	<h4>可选权限</h4>
<?php if(empty($no_list)): ?>
	<p class="bg-danger padding10">无权限</p>
<?php else:
	foreach($no_list as $v):?>
		<button class="btn btn-primary"
				onclick="access_add(<?php echo $v?>,'<?php echo htmlspecialchars($__permission[$v])?>')"><?php echo $__permission[$v]?></button>
	<?php endforeach; endif; ?>
	<div id="AccessAdd" style="display: none;">
		<form action="<?php echo get_url('Access', 'add_access') ?>" method="post" class="inner_form">
			<div class="form-group">
				<label class="control-label sr-only" for="DisName">名称</label>
				<input type="text" disabled class="form-control AddDN" id="DisName">
				<input type="hidden" name="r_id">
				<input type="hidden" name="p_id">
			</div>
			<div class="form-group">

				<div class="form-control">
					<label>权限 : </label>
					<label class="checkbox-inline"><input type="checkbox" value="1" name="write">写</label>
					<label class="checkbox-inline"><input type="checkbox" value="1" name="read">读</label>
				</div>
			</div>
			<div class="form-group text-right">
				<button class="btn btn-primary">添加</button>
			</div>
		</form>
	</div>
	<script>
		var role = <?php echo json_encode($__role)?>;
		function access_add(id, name) {
			modal_show("添加权限", $("#AccessAdd").html(), {
				type: 'shown', call: function () {
					$(".AddDN").val("添加 " + name + " 到 " + role.r_name);
					$("input[name=r_id]").val(role.r_id);
					$("input[name=p_id]").val(id);
					$(".inner_form").ajaxForm(function (data) {
						if (data.status) {
							location.reload();
						} else {
							alert(data.msg);
						}
					});
				}
			});
		}
		function access_del(id) {
			if (confirm("确定删除？")) {
				$.post("<?php echo get_url("Access","delete_access")?>", {p_id: id, r_id: role.r_id}, function (data) {
					if (data.status) {
						location.reload();
					} else {
						alert(data.msg);
					}
				});
			}
		}
	</script>
<?php
$this->get_footer();