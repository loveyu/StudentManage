<?php
/**
 * @var $this      \UView\Report
 * @var $__info    array
 * @var $__mc_list array
 * @var $__table   array
 * @var $__mc_s   array
 */
$this->get_header(); ?>
	<h1>查询成绩单</h1>
	<ol class="breadcrumb">
		<li><?php echo $__info['ic_name'] ?></li>
		<li>学院：<?php echo $__info['ico_name'] ?></li>
		<li>专业：<?php echo $__info['id_name'] ?> [<?php echo $__mc_s['id_id']?>]</li>
		<li>年级：<?php echo $__info['mc_grade'] ?></li>
		<li>学年：<?php echo $__info['mc_year'] ?></li>
		<li>学期：<?php echo $__info['mc_number'] ?></li>
	</ol>
	<table class="table table-bordered table-hover report">
		<thead>
		<tr>
			<th rowspan="2">学号</th>
			<th rowspan="2">姓名</th>
			<th rowspan="2">班级</th>
			<?php foreach($__mc_list as $mc_id => $cu_name): ?>
				<th colspan="3"><?php echo $cu_name ?></th>
			<?php endforeach; ?>
		</tr>
		<tr>
			<?php foreach($__mc_list as $mc_id): ?>
				<th>平时</th>
				<th>考试</th>
				<th>总分</th>
			<?php endforeach; ?>
		</tr>
		</thead>
		<tbody>
		<?php foreach($__table as $is_id => $info): ?>
			<tr>
				<td><?php echo $is_id?></td>
				<td><?php echo $info['name']?></td>
				<td><?php echo $info['class']?></td>
				<?php foreach(array_keys($__mc_list) as $mic):
					if(!isset($info['list'][$mic])){
						echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
					}else{
					?>
					<td><?php echo $info['list'][$mic]['work']?></td>
					<td><?php echo $info['list'][$mic]['test']?></td>
					<td><?php echo $info['list'][$mic]['total']?></td>
				<?php } endforeach;?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php
$this->get_footer();