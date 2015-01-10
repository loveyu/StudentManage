<?php
/**
 * @var $this        \UView\Access
 * @var $__role_list array
 */
$this->get_header();
?>
	<h3>选择对应的角色</h3>
<?php if(isset($__role_list[0])): ?>
	<p>
		<?php foreach($__role_list as $v): ?>
			<a class="btn btn-success" href="<?php echo get_url('Access', 'access_set'), "?id=", $v['r_id'] ?>"><?php echo $v['r_name'] ?></a>
		<?php endforeach; ?>
	</p>
<?php else: ?>
	<h4 class="bg-danger not_found">无角色</h4>
<?php endif; ?>
<?php
$this->get_footer();