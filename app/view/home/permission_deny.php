<?php send_http_status(403); ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>无访问权限</title>
	<link rel="stylesheet" href="<?php echo $this->get_bootstrap('css/bootstrap.min.css'); ?>"/>
</head>
<body>
<div class="container">
	<h1>拒绝访问</h1>

	<div class="well">
		<p class="text-danger">你没有访问该页面的权限！</p>
	</div>
</div>
</body>
</html>