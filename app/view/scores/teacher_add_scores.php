<?php
/**
 * @var $this   \UView\Scores
 * @var $__list array
 * @var $__info array
 */
$this->get_header();
?>
	<h1>专业分数添加</h1>
	<ol class="breadcrumb">
		<li><?php echo $__info['ic_name']?></li>
		<li>学院：<?php echo $__info['ico_name']?></li>
		<li>专业：<?php echo $__info['id_name']?></li>
		<li>年级：<?php echo $__info['mc_grade']?></li>
		<li>学年：<?php echo $__info['mc_year']?></li>
		<li>学期：<?php echo $__info['mc_number']?></li>
		<li>课程：<?php echo $__info['cu_name']?></li>
	</ol>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>学号</th>
			<th>姓名</th>
			<th>班级</th>
			<th>平时成绩</th>
			<th>考试成绩</th>
			<th>总成绩</th>
			<th>操作</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($__list as $id => $v): ?>
			<tr id="TR_<?php echo $id ?>">
				<td><?php echo $v['is_id'] ?></td>
				<td><?php echo $v['is_name'] ?></td>
				<td><?php echo $v['icl_number'] ?></td>
				<td><input class="form-control" name="work_<?php echo $id ?>" type="number" value="<?php echo $v['sc_work'] ?>"></td>
				<td><input class="form-control" name="test_<?php echo $id ?>" type="number" value="<?php echo $v['sc_test'] ?>"></td>
				<td id="Total_<?php echo $id ?>"><?php echo $v['sc_total'] ?></td>
				<td>
					<button class="btn btn-sm btn-warning" onclick="up(<?php echo $id ?>)">更新</button>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<div class="form-group">
		<button type="button" class="btn btn-danger" onclick="update_all()">全部更新</button>
	</div>
<?php for($i = 0; $i < count($__list); $i++){
	unset($__list[$i]['is_name'], $__list[$i]['id_name'], $__list[$i]['icl_number']);
} ?>
	<script>
		var list = <?php echo json_encode($__list,JSON_UNESCAPED_UNICODE)?>;
		jQuery(function ($) {
			$("input").change(function () {
				var id = $(this).attr("name").substr(5);
				var work = $("input[name=work_" + id + "]").val();
				var test = $("input[name=test_" + id + "]").val();
				$("#Total_" + id).html(work * 0.2 + test * 0.8);
			});
		});
		function update_all() {
			$.each(list, function (id, value) {
				var work = $("input[name=work_" + id + "]").val();
				var test = $("input[name=test_" + id + "]").val();
				if (work == value.sc_work && test == value.sc_test) {
					return;
				}
				if (work === "" && test === "") {
					return;
				}
				$("#TR_" + id).removeClass("danger");
				$.post("<?php echo get_url("Scores","teacher_add_scores_ajax")?>",
					{mc_id: value.mc_id, is_id: value.is_id, sc_work: work, sc_test: test}, function (data) {
						if (data.status) {
							list[id].sc_test = data.msg.sc_test;
							list[id].sc_work = data.msg.sc_work;
							list[id].sc_total = data.msg.sc_total;
							$("#TR_" + id).addClass("success");
							setTimeout(function () {
								$("#TR_" + id).removeClass("success");
							}, 2000);
						} else {
							$("#TR_" + id).addClass("danger");
							alert(data.msg);
						}
					});
			});
		}
		function up(id) {
			var work = $("input[name=work_" + id + "]").val();
			var test = $("input[name=test_" + id + "]").val();
			if (work == list[id].sc_work && test == list[id].sc_test) {
				alert("无修改");
				return;
			}
			$("#TR_" + id).removeClass("danger");
			$.post("<?php echo get_url("Scores","teacher_add_scores_ajax")?>",
				{mc_id: list[id].mc_id, is_id: list[id].is_id, sc_work: work, sc_test: test}, function (data) {
					if (data.status) {
						list[id].sc_test = data.msg.sc_test;
						list[id].sc_work = data.msg.sc_work;
						list[id].sc_total = data.msg.sc_total;
						$("#TR_" + id).addClass("success");
						setTimeout(function () {
							$("#TR_" + id).removeClass("success");
						}, 2000);
					} else {
						$("#TR_" + id).addClass("danger");
						alert(data.msg);
					}
				});
		}
	</script>
<?php
$this->get_footer();