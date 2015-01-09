<?php
/**
 * @var $this \UView\Profile
 */
?>
<form action="<?php echo get_url([
	'Profile',
	'edit_pwd'
]) ?>" method="post" id="EditPwdAction" style="max-width: 400px;margin: 0 auto">
	<div class="form-group">
		<label class="control-label" for="OldPwd">原密码</label>
		<input class="form-control" type="password" name="old_pwd" id="OldPwd">
	</div>
	<div class="form-group">
		<label class="control-label" for="NewPwd">新密码</label>
		<input class="form-control" type="password" name="new_pwd" id="NewPwd">
	</div>
	<div class="form-group">
		<label class="control-label" for="NewCPwd">确认新密码</label>
		<input class="form-control" type="password" name="new_c_pwd" id="NewCPwd">
	</div>
	<div class="form-group text-right">
		<button type="submit" class="btn btn-danger">修改密码</button>
	</div>
</form>
<script>
	jQuery(function ($) {
		$("#EditPwdAction").ajaxForm(function (data) {
			if(data.status){
				$("#EditPwdAction").html("<p class='text-success bg-primary' style='padding: 10px 25px'>修改成功！</p>")
			}else{
				alert(data.msg);
			}
		});
	});
</script>