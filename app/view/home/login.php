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
	<script type="text/javascript" src="<?php echo $this->get_asset('js/jquery.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo $this->get_bootstrap('js/bootstrap.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo $this->get_asset("js/jquery.form.js") ?>"></script>
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
			<?php if($_SERVER['HTTP_HOST']==="demo.loveyu.net"):?>
			<p class="text-warning">
				默认管理员：<code>loveyu</code>,<code>help</code>，密码：<code>123456</code>
			</p>
			<?php endif; ?>
			<div class="form-group">
				<label class="sr-only" for="user_name">用户：</label>

				<div class="input-group">
					<div class="input-group-addon"><label class="glyphicon glyphicon-user" for="user_name"></label></div>
					<input class="form-control" placeholder="学号/教师号/管理员账号" type="text" id="user_name" value="<?php echo htmlspecialchars(req()->post('user_name')) ?>"
						   name="user_name"/>
				</div>
			</div>
			<div class="form-group">
				<label class="sr-only" for="user_pwd">密码：</label>

				<div class="input-group">
					<div class="input-group-addon"><label class="glyphicon glyphicon-lock" for="user_name"></label></div>
					<input class="form-control" placeholder="密码" type="password" id="user_pwd" name="user_pwd"/>
				</div>
			</div>
			<div class="form-group">
				<label class="sr-only">类型</label>

				<div class="input-group">
					<div class="input-group-addon">类型</div>
					<div class="form-control">
						<?php echo html_radio([
							'student' => "学生",
							"teacher" => "教师",
							"admin" => "管理员"
						], "login_type", req()->post('login_type'), "student", "<label>", "&nbsp;&nbsp;</label>\n") ?>
					</div>
				</div>
			</div>

			<div class="form-group text-right">
				<button class="btn btn-default" type="submit">登陆</button>
			</div>
		</fieldset>
	</form>
</div>
<?php include __DIR__."/../common/footer.php"?>
</body>
</html>
