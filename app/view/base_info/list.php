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
<?php if(isset($__info['search'])){
	echo "<form class=\"form-inline\" method=\"get\" action='" . get_url("BaseInfo", "op", $__type, "list") . "'>";
	$__info['search']['order'] = [
		'name' => '排序',
		'type' => 'select',
		'list' => list2keymapSK($__info['filed'], "name")
	];
	$__info['search']['sort'] = [
		'name' => '排序方式',
		'type' => 'select',
		'list' => [
			'ASC' => '顺序',
			'DESC' => '倒序',
		],
	];
	foreach($__info['search'] as $search_name => $search){
		$is_like = (isset($search['like']) && $search['like'] == 1) ? "(*)" : "";
		?>
		<div class="form-group">
			<label class="sr-only" for="ID_<?php echo $search_name ?>"><?php echo $search['name']?></label>

			<div class="input-group">
				<div class="input-group-addon"><?php echo $search['name'], $is_like?></div>
				<?php
				switch($search['type']){
					case "text":
						?><input<?php echo isset($search['size']) ? (" size=\"" . $search['size'] . "\" ") : ""?> name="<?php echo $search_name?>"
																												  value="<?php echo $__query->search_value($search_name)?>"
																												  type="text" class="form-control"
																												  id="ID_<?php echo $search_name ?>"><?php
						break;
					case "select":
						?>
						<select name="<?php echo $search_name?>" class="form-control" id="ID_<?php echo $search_name ?>">
							<option value="">&nbsp;</option>
							<?php echo html_option(isset($search['list_call']) ? call_user_func($search['list_call']) : (isset($search['list']) ? $search['list'] : []), $__query->search_value($search_name)) ?>
						</select>

						<?php
						break;
				}
				?>
			</div>
		</div>

	<?php }
	echo "<button type=\"submit\" class=\"btn btn-primary\">查询</button></form>";
}
if(!$__query->has_data()):
	echo "<h4 class='bg-danger not_found'>数据未找到</h4>";
else:
	echo "<table class='table table-striped  table-hover'" . (isset($__info['list_style']) ? " style=\"{$__info['list_style']}\"" : "") . "><thead><tr><th>操作</th>";
	foreach($__query->getFiled() as $name => $v){
		if(isset($__info['filed'][$name]['no_out'])){
			continue;
		}
		echo "<th>{$v}</th>";
	}
	echo "</tr></thead><tbody>";
	$i = 0;
	$pks = [];
	foreach($__query->getData() as $v){
		$pks[$i] = [];
		echo "<tr id=\"TR_ID_{$i}\"><td><a href='#' class='btn btn-warning btn-sm' onclick=\"return edit({$i})\">编辑</a>", "<a class='btn btn-danger btn-sm' href='#' onclick=\"return del({$i})\">删除</a></td>";
		foreach($v as $name => $value){
			if(isset($__info['filed'][$name]['no_out'])){
				continue;
			}
			if(isset($__info['filed'][$name]['pk'])){
				$pks[$i][$name] = $value;
			}
			if(isset($__info['filed'][$name]['out_call'])){
				echo "<td>" . $__info['filed'][$name]['out_call']($value) . "</td>";
			} else{
				echo "<td>{$value}</td>";
			}
		}
		echo "</tr>";
		$i++;
	}
	echo "</tbody></table>";
	echo $__query->get_page_nav();
endif; ?>
	<script>
		var obj_info = <?php echo json_encode($pks,JSON_UNESCAPED_UNICODE)?>;
		function del(i) {
			if (confirm("你确定删除选中的数据么？")) {
				$.post("<?php echo get_url("BaseInfo","del",$__type)?>", obj_info[i], function (data) {
					if (data.status) {
						$("#TR_ID_" + i).slideUp("slow", function () {
							$(this).remove();
						});
					} else {
						alert(data.msg);
					}
				});
			}
			return false;
		}
		function edit(n) {
			var url = "<?php echo get_url("BaseInfo",'op',$__type,"edit")?>?";
			$.each(obj_info[n], function (index, value) {
				url += index + "=" + encodeURI(value);
			});
			location.href = url;
			return false;
		}
	</script>
<?php
$this->get_footer();