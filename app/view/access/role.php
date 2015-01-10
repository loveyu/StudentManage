<?php
/**
 * @var $this   \UView\Access
 * @var $__list array
 */
$this->get_header(); ?>
	<h3>角色列表
		<button class="btn btn-primary btn-sm" onclick="role_add()">添加</button>
	</h3>
<?php if(isset($__list[0])): ?>
	<div id="RoleTable">
		<table class="table table-striped  table-hover">
			<thead>
			<tr>
				<th>ID</th>
				<th>名称</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($__list as $v): ?>
				<tr>
					<td><?php echo $v['r_id'] ?></td>
					<td><?php echo $v['r_name'] ?></td>
					<td><?php echo role_status($v['r_status']) ?></td>
					<td>
						<button class="btn btn-default btn-sm"
								onclick="role_edit(<?php echo $v['r_id'] ?>,'<?php echo htmlspecialchars($v['r_name']) ?>',<?php echo $v['r_status'] ?>)">
							编辑
						</button>
						<?php if($v['r_id'] != 1): ?>
						<button class="btn btn-danger btn-sm" onclick="role_del(<?php echo $v['r_id'] ?>)">删除</button><?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<h4 class="bg-danger not_found">无角色</h4>
<?php endif; ?>
	<div id="AddRole" style="display: none">
		<form action="<?php echo get_url('Access', 'add_role') ?>" method="post" class="inner_form">
			<div class="form-group">
				<label class="control-label" for="AddRoleName">名称</label>
				<input type="text" name="name" class="form-control" id="AddRoleName">
			</div>
			<div class="form-group">
				<label class="control-label" for="AddRoleStatus">状态</label>
				<select id="AddRoleStatus" name="status" class="form-control">
					<option value="0">启用</option>
					<option value="1">禁用</option>
				</select>
			</div>
			<div class="form-group text-right">
				<button class="btn btn-primary">添加</button>
			</div>
		</form>
	</div>
	<div id="EditRole" style="display: none">
		<form action="<?php echo get_url('Access', 'edit_role') ?>" method="post" class="inner_form">
			<div class="form-group">
				<label class="control-label" for="EditRoleName">名称</label>
				<input type="text" name="name" class="form-control EditRoleName" id="EditRoleName">
			</div>
			<div class="form-group">
				<label class="control-label" for="EditRoleStatus">状态</label>
				<select id="EditRoleStatus" name="status" class="form-control EditRoleStatus">
					<option value="0">启用</option>
					<option value="1">禁用</option>
				</select>
			</div>

			<div class="form-group text-right">
				<button class="btn btn-primary">编辑</button>
			</div>
			<input type="hidden" name="id" value="" id="EditRoleId" class="EditRoleId">
		</form>
	</div>
	<script>
		function role_add() {
			modal_show("添加角色", $("#AddRole").html(), {
				type: 'shown', call: function () {
					$(".inner_form").ajaxForm(function (data) {
						if (data.status) {
							location.reload();
						} else {
							alert(data.msg);
						}
					});
				}
			});
		}
		function role_edit(id, name, status) {
			modal_show("编辑角色", $("#EditRole").html(), {
				type: 'shown', call: function () {
					$(".EditRoleName").val(name);
					$(".EditRoleId").val(id);
					$(".EditRoleStatus").val(status);
					$(".inner_form").ajaxForm(function (data) {
						if (data.status) {
							location.reload();
						} else {
							alert(data.msg);
						}
					});
				}
			});
		}
		function role_del(id) {
			if (confirm("确定删除？")) {
				$.post("<?php echo get_url("Access","delete_role")?>", {id: id}, function (data) {
					if (data.status) {
						location.reload();
					} else {
						alert(data.msg);
					}
				});
			}
		}

	</script>
<?php
$this->get_footer();