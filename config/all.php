<?php
return [
	'database' => [
		'database_type' => 'mysql',
		'server' => 'localhost',
		'username' => 'root',
		'password' => '123456',
		'charset' => 'utf8',
		'database_name' => 'sm',
		'option' => [ //PDO选项
					  PDO::ATTR_CASE => PDO::CASE_NATURAL,
					  PDO::ATTR_TIMEOUT => 5
		]
	],
	'menu' => [
		[
			'url' => [],
			'name' => '我的信息',
		],
		[
			'url' => [
				'Access',
				'role'
			],
			'name' => '角色管理',
			'role' => 1
		],
		[
			'url' => [
				'Access',
				'admin'
			],
			'name' => '用户管理',
			'role' => 1
		],
		[
			'url' => [
				'Access',
				'permission'
			],
			'name' => '权限管理',
			'role' => 1
		],
		[
			'url' => [
				'Access',
				'access_set'
			],
			'name' => '角色权限管理',
			'role' => 1
		],
		[
			'url' => [
				'Home',
				'logout'
			],
			'name' => '退出登录'
		]
	]
];