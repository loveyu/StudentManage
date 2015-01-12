<?php
/**
 * @var $this    \UView\BaseInfo
 * @var $__info  array
 * @var $__type  string
 * @var $__query \ULib\QueryList
 */
$this->get_header(); ?>
	<h3><?php echo $__info['name'] ?>
		<?php if(access_class()->write($__type)): ?><a class="btn btn-primary btn-sm" href="<?php echo get_url('BaseInfo', 'op', $__type, "add") ?>">
				添加</a><?php endif; ?>
	</h3>
<?php
if(!$__query->has_data()):
	echo "<h4 class='bg-danger not_found'>数据未找到</h4>";
else:
	echo "<table class='table table-striped  table-hover'><thead><tr>";
	foreach($__query->getFiled() as $name=> $v){
		if(isset($__info['filed'][$name]['no_out'])){
			continue;
		}
		echo "<th>{$v}</th>";
	}
	echo "</tr></thead><tbody>";
	foreach($__query->getData() as $v){
		echo "<tr>";
		foreach($v as $name => $value){
			if(isset($__info['filed'][$name]['no_out'])){
				continue;
			}
			if(isset($__info['filed'][$name]['out_call'])){
				echo "<td>" . $__info['filed'][$name]['out_call']($value) . "</td>";
			} else{
				echo "<td>{$value}</td>";
			}

		}
		echo "</tr>";
	}
	echo "</tbody></table>";
	echo $__query->get_page_nav();
endif; ?>
<?php
$this->get_footer();