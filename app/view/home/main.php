<?php
/**
 * @var $this          \UView\Home
 * @var $__role_access array
 * @var $__filed       array
 */
$detail = $this->get_user_detail();
$this->get_header();
?>
	<h1 class="title">登录基本信息</h1>
	<dl class="dl-horizontal user-info">
		<dt>基本信息</dt>
		<dd>
			<strong><?php echo $detail['user']['name'] ?></strong>[<?php echo $detail['user']['id'] ?>
			]<i>(<?php echo user_status($detail['user']['status']) ?>
				)</i>，<?php echo role_info($detail['role']) ?>
		</dd>
		<?php switch($detail['user']['login_type']){
			case "admin":
				?>
				<dt>当前IP</dt>
				<dd><?php echo $detail['user']['ip'] ?></dd>
				<dt>允许IP</dt>
				<dd><p><?php echo implode("<br />", $detail['user']['ip_list']) ?></p></dd>
				<?php
				break;
			case "student":
			case "teacher":
				foreach($__filed as $n=>$v){
					if((isset($v['no_out']) && $v['no_out']) || !isset($detail['user'][$n]))continue;
					echo "<dt>{$v['name']}</dt>";
					echo "<dd>{$detail['user'][$n]}</dd>";
				}
				break;
		} ?>
		<dt>&nbsp;</dt>
		<dd>
			<button id="EditPassword" type="button" class="btn btn-warning">修改密码</button>
		</dd>
		<?php if(!empty($__role_access)): ?>
			<dt>权限列表</dt>
			<dd style="line-height: 2.5;vertical-align: top"><?php foreach($__role_access as $v): ?>
					<span class="btn btn-sm btn-success"><?php echo $v['p_alias'] ?>&nbsp;[<?php echo $v['p_name'] ?>
						]&nbsp;(<?php echo access_status($v) ?>)
					</span>
				<?php endforeach; ?></dd>
		<?php endif; ?>
	</dl>
	<script>
		jQuery(function ($) {
			$("#EditPassword").click(function () {
				$.get("<?php echo get_url('Profile','edit_pwd')?>", function (data) {
					modal_show("修改密码", data);
				});
			});
		});
	</script>
<?php
$this->get_footer();
