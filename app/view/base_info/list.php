<?php
/**
 * @var $this \UView\BaseInfo
 * @var $__info array
 * @var $__type string
 * @var $__query \ULib\QueryList
 */
$this->get_header();?>
	<h3><?php echo $__info['name']?>
		<a class="btn btn-primary btn-sm" href="<?php echo get_url('BaseInfo','op',$__type,"add")?>">添加</a>
	</h3>
<?php
$this->get_footer();