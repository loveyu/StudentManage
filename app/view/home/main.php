<?php
/**
 * @var $this          \UView\Home
 * @var $__role_access array
 */
$detail = $this->get_user_detail();
$this->get_header();
?>
	<dl class="dl-horizontal user-info">
		<dt>基本信息</dt>
		<dd>
			<strong><?php echo $detail['user']['name'] ?></strong><i>(<?php echo user_status($detail['user']['status']) ?>
				)</i>，<?php echo role_info($detail['role']) ?>
		</dd>
		<dt>当前IP</dt>
		<dd><?php echo $detail['user']['ip'] ?></dd>
		<dt>允许IP</dt>
		<dd><p><?php echo implode("<br />", $detail['user']['ip_list']) ?></p></dd>
		<dt>&nbsp;</dt>
		<dd>
			<button id="EditPassword" type="button" class="btn btn-warning">修改密码</button>
		</dd>
		<?php if(!empty($__role_access)): ?>
			<dt>权限列表</dt>
			<dd><?php foreach($__role_access as $v): ?>
					<button class="btn btn-danger btn-sm"><?php echo $v['p_alias'] ?>&nbsp;[<?php echo $v['p_name'] ?>]&nbsp;(<?php echo access_status($v) ?>)
					</button>
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
