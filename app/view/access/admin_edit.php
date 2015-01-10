<?php
/**
 * @var $this        \UView\Access
 * @var $__info      array
 * @var $__role_list array
 */
$this->get_header();
?>
	<h4>修改用户信息</h4>
	<form id="EditAdmin" action="<?php echo get_url('Access', 'edit_admin') ?>" method="post" style="max-width: 600px">
		<div class="form-group">
			<label class="control-label sr-only" for="EditAdminID">ID</label>
			<input type="text" disabled class="form-control" id="EditAdminID" value="ID : <?php echo $__info['a_id'] ?>">
			<input type="hidden" name="id" value="<?php echo $__info['a_id'] ?>">
		</div>
		<div class="form-group">
			<label class="control-label" for="EditAdminName">名称</label>
			<input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($__info['a_name']) ?>" id="EditAdminName">
		</div>
		<div class="form-group">
			<label class="control-label" for="EditAdminStatus">状态</label>
			<select id="EditAdminStatus" name="status" class="form-control">
				<?php echo html_option([
					0 => '启用',
					1 => '限制登录',
					2 => '限制权限'
				], $__info['a_status']); ?>
			</select>
		</div>
		<div class="form-group">
			<label class="control-label" for="EditAdminRole">角色</label>
			<select id="EditAdminRole" name="role" class="form-control">
				<?php echo html_option($__role_list, $__info['r_id']) ?>
			</select>
		</div>
		<div class="form-group">
			<label class="control-label" for="EditAdminIP">允许的IP访问
				<small class="text-danger">每行一个，*为通配符，必须存在，否则禁止登录</small>
			</label>
			<textarea class="form-control" id="EditAdminIP"
					  name="ip"><?php echo htmlspecialchars(implode("\n", explode("|", $__info['a_ip']))) ?></textarea>
		</div>
		<div class="form-group text-right">
			<button class="btn btn-primary" type="submit">更新</button>
		</div>
	</form>
<script>
	jQuery(function($){
		$("#EditAdmin").ajaxForm(function(data){
			if(data.status){
				modal_show("更新成功","<p class='text-success'>成功更新</p>");
			}else{
				alert(data.msg);
			}
		});
	});
</script>
<?php
$this->get_footer();