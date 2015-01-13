<?php
/**
 * @var $this  \UView\Home
 * @var $__msg string 登陆失败信息
 */
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $this->getTitle() ?></title>
	<link rel="stylesheet" href="<?php echo $this->get_bootstrap('css/bootstrap.min.css'); ?>"/>
	<link rel="stylesheet" href="<?php echo $this->get_bootstrap('css/bootstrap-theme.min.css'); ?>"/>
	<link rel="stylesheet" href="<?php echo $this->get_asset('css/style.css'); ?>"/>
	<link rel="stylesheet" href="<?php echo $this->get_bootstrap('js/bootstrap.min.js'); ?>"/>
</head>
<body class="login-bg container-fluid">
<div class="row">
<h1 class="col-md-8 col-md-offset-2">长江大学
	<small>学籍管理系统</small>
</h1>
</div>
<div class="row">
<form action="" method="post" class="login-form col-sm-3 col-sm-offset-7">
	<fieldset>
		<legend>用户登录</legend>
		<?php if(isset($__msg) && !empty($__msg)): ?>
			<p class="text-danger"><b><?php echo $__msg ?></b></p>
		<?php endif; ?>
		<div class="form-group">
			<label class="control-label" for="user_name">用户：</label>
			<input class="form-control" type="text" id="user_name" value="<?php echo htmlentities(req()->post('user_name')) ?>" name="user_name"/>
		</div>
		<div class="form-group">
			<label class="control-label" for="user_pwd">密码：</label>
			<input class="form-control" type="password" id="user_pwd" name="user_pwd"/>
		</div>
		<div class="form-group text-right">
			<button class="btn btn-default" type="submit">登陆</button>
		</div>
	</fieldset>
</form>
</div>
</body>
</html>
