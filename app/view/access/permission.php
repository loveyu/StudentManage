<?php
/**
 * @var $this   \UView\Access
 * @var $__list array
 */
$this->get_header(); ?>
	<h3>权限管理
		<button class="btn btn-primary btn-sm" onclick="permission_add()">添加</button>
	</h3>
<?php if(isset($__list[0])): ?>
	<div id="PermissionTable">
		<table class="table table-striped  table-hover">
			<thead>
			<tr>
				<th>ID</th>
				<th>别名</th>
				<th>名称</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($__list as $v): ?>
				<tr>
					<td><?php echo $v['p_id'] ?></td>
					<td><?php echo $v['p_alias'] ?></td>
					<td><?php echo $v['p_name'] ?></td>
					<td><?php echo permission_status($v['p_status']) ?></td>
					<td>
						<button class="btn btn-default btn-sm"
								onclick="permission_edit(<?php echo $v['p_id'] ?>,'<?php echo htmlspecialchars($v['p_alias']) ?>','<?php echo htmlspecialchars($v['p_name']) ?>',<?php echo $v['p_status'] ?>)">
							编辑
						</button>
						<button class="btn btn-danger btn-sm" onclick="permission_del(<?php echo $v['p_id'] ?>)">删除</button>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<h4 class="bg-danger not_found">无权限</h4>
<?php endif; ?>
	<div id="AddPermission" style="display: none">
		<form action="<?php echo get_url('Access', 'add_permission') ?>" method="post" class="inner_form">
			<div class="form-group">
				<label class="control-label" for="AddPermissionName">名称
					<small>a-z_</small>
				</label>
				<input type="text" name="name" class="form-control" id="AddPermissionName">
			</div>
			<div class="form-group">
				<label class="control-label" for="AddPermissionAlias">别名,中文名称
				</label>
				<input type="text" name="alias" class="form-control" id="AddPermissionAlias">
			</div>
			<div class="form-group">
				<label class="control-label" for="AddPermissionStatus">状态</label>
				<select id="AddPermissionStatus" name="status" class="form-control">
					<option value="0">启用</option>
					<option value="1">禁用</option>
				</select>
			</div>
			<div class="form-group text-right">
				<button class="btn btn-primary">添加</button>
			</div>
		</form>
	</div>
	<div id="EditPermission" style="display: none">
		<form action="<?php echo get_url('Access', 'edit_permission') ?>" method="post" class="inner_form">
			<div class="form-group">
				<label class="control-label" for="EditPermissionName">名称
					<small>a-z_</small>
				</label>
				<input type="text" name="name" class="form-control EditPermissionName" id="EditPermissionName">
			</div>
			<div class="form-group">
				<label class="control-label" for="AddPermissionAlias">别名
				</label>
				<input type="text" name="alias" class="form-control EditPermissionAlias" id="AddPermissionAlias">
			</div>
			<div class="form-group">
				<label class="control-label" for="EditPermissionStatus">状态</label>
				<select id="EditPermissionStatus" name="status" class="form-control EditPermissionStatus">
					<option value="0">启用</option>
					<option value="1">禁用</option>
				</select>
			</div>

			<div class="form-group text-right">
				<button class="btn btn-primary">编辑</button>
			</div>
			<input type="hidden" name="id" value="" id="EditPermissionId" class="EditPermissionId">
		</form>
	</div>
	<script>
		function permission_add() {
			modal_show("添加权限", $("#AddPermission").html(), {
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
		function permission_edit(id, alias, name, status) {
			modal_show("编辑权限", $("#EditPermission").html(), {
				type: 'shown', call: function () {
					$(".EditPermissionName").val(name);
					$(".EditPermissionAlias").val(alias);
					$(".EditPermissionId").val(id);
					$(".EditPermissionStatus").val(status);
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
		function permission_del(id) {
			if (confirm("确定删除？")) {
				$.post("<?php echo get_url("Access","delete_permission")?>", {id: id}, function (data) {
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