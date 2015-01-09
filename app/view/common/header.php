<?php
/**
 * @var $this ULib\Page
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
<body class="my-body">
<div class="container-fluid">
	<div class="row my-content">
		<div class="col-md-2" id="LeftMenu">
			<h3>导航控制</h3>
			<ul class="nav nav-pills nav-stacked">
				<?php echo $this->get_user_menu()?>
			</ul>
		</div>
		<div class="col-md-10" id="RightContent">
			<h1 class="title">学籍管理系统</h1>