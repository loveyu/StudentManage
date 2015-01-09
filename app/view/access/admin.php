<?php
/**
 * @var $this        \UView\Access
 * @var $__list      array
 * @var $__role_list array
 */
$this->get_header();
?>
	<h3>管理员列表
		<button class="btn btn-primary btn-sm" onclick="admin_add()">添加</button>
	</h3>
<?php if(isset($__list[0])): ?>
	<div id="RoleTable">
		<table class="table table-striped  table-hover">
			<thead>
			<tr>
				<th>ID</th>
				<th>名称</th>
				<th>状态</th>
				<th>用户组</th>
				<th>允许IP</th>
				<th>操作</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($__list as $v): ?>
				<tr>
					<td><?php echo $v['a_id'] ?></td>
					<td><?php echo $v['a_name'] ?></td>
					<td><?php echo user_status($v['a_status']) ?></td>
					<td><?php echo $v['r_name'] ?>(<?php echo role_status($v['r_status']) ?>)</td>
					<td><?php echo str_replace("|", "<br />", $v['a_ip']) ?></td>
					<td>
						<a class="btn btn-default btn-sm"
								href="<?php echo get_url("Access","admin")."?edit_id=".$v['a_id']?>">
							编辑
						</a>
						<button class="btn btn-danger btn-sm" onclick="admin_del(<?php echo $v['a_id'] ?>)">删除</button>
						<button class="btn btn-danger btn-sm" onclick="admin_pwd_reset(<?php echo $v['a_id'] ?>)">密码重置</button>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<h4 class="bg-danger">无角色</h4>
<?php endif; ?>
	<div id="AddAdmin" style="display: none">
		<form action="<?php echo get_url('Access', 'add_admin') ?>" method="post" class="inner_form">
			<div class="form-group">
				<label class="control-label" for="AddAdminName">姓名</label>
				<input type="text" name="name" class="form-control" id="AddAdminName">
			</div>
			<div class="form-group">
				<label class="control-label" for="AddAdminPWD">密码</label>
				<input type="password" name="pwd" class="form-control" id="AddAdminPWD">
			</div>
			<div class="form-group">
				<label class="control-label" for="AddAdminStatus">状态</label>
				<select id="AddAdminStatus" name="status" class="form-control">
					<option value="0">启用</option>
					<option value="1">限制登录</option>
					<option value="2">无权限</option>
				</select>
			</div>
			<div class="form-group">
				<label class="control-label" for="AddAdminRole">角色</label>
				<select id="AddAdminRole" name="role" class="form-control">
					<?php echo html_option($__role_list, "") ?>
				</select>
			</div>
			<div class="form-group">
				<label class="control-label" for="AddAdminIP">允许的IP访问
					<small class="text-danger">每行一个，*为通配符，必须存在，否则禁止登录</small>
				</label>
				<textarea class="form-control" id="AddAdminIP" name="ip">192.168.*</textarea>
			</div>
			<div class="form-group text-right">
				<button class="btn btn-primary">添加</button>
			</div>
		</form>
	</div>

	<script>
		function admin_add() {
			modal_show("添加角色", $("#AddAdmin").html(), {
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
		function admin_del(id) {
			if (confirm("确定删除？")) {
				$.post("<?php echo get_url("Access","delete_admin")?>", {id: id}, function (data) {
					if (data.status) {
						location.reload();
					} else {
						alert(data.msg);
					}
				});
			}
		}
		function admin_pwd_reset(id) {
			if (confirm("确定重置该用户的密码？")) {
				$.post("<?php echo get_url("Access","admin_pwd_reset")?>", {id: id}, function (data) {
					if (data.status) {
						modal_show("该用户新密码为","<p class=\"well well-sm inner_form\"><code>"+data.msg+"</code></p>");
					} else {
						alert(data.msg);
					}
				});
			}
		}

	</script>
<?php
$this->get_footer();