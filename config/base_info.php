<?php
return [
	'campus_info' => [
		'name' => '校园信息',
		'table' => 'info_campus',
		'filed' => [
			'ic_name' => [
				'name' => '名称',
				'type' => 'text',
				'vt' => 'text',
				'pk' => 1,
				'check' => ['not_empty']
			],
			'ic_address' => [
				'name' => '地址',
				'type' => 'text',
				'vt' => 'text',
				'edit' => 1,
				'check' => ['not_empty']
			],
			'ic_postcode' => [
				'name' => '邮编',
				'type' => 'text',
				'vt' => 'number',
				'edit' => 1,

				'rule' => '/^[0-9]{3,10}$/'
			],
			'ic_tel' => [
				'name' => '电话',
				'type' => 'text',
				'vt' => 'number',
				'edit' => 1,
				'check' => ['is_tel']
			],
		]
	],
	'college_info' => [
		'name' => '学院信息',
		'table' => 'info_college',
		'search' => [
			'ic_name' => [
				'name' => '校区',
				'type' => 'select',
				'list_call' => 'get_campus_info'
			]
		],
		'filed' => [
			'ico_id' => [
				'name' => '编号',
				'type' => 'text',
				'vt' => 'text',
				'pk' => 1,
				'rule' => '/^[0-9]{3,10}$/',
				'check' => ['not_empty']
			],
			'ico_name' => [
				'name' => '名称',
				'type' => 'text',
				'vt' => 'text',
				'edit' => 1,
				'check' => ['not_empty']
			],
			'ic_name' => [
				'name' => '校区',
				'type' => 'select',
				'check_func' => 'check_campus_info',
				'select_func' => 'get_campus_info'
			],
			'ico_teacher' => [
				'name' => '主要负责人',
				'type' => 'text',
				'vt' => 'text',
				'edit' => 1,
				'check' => ['not_empty']
			],
			'ico_tel' => [
				'name' => '电话',
				'type' => 'text',
				'vt' => 'number',
				'edit' => 1,
				'check' => ['is_tel']
			],
		]
	],
	'discipline_info' => [
		'name' => '专业信息',
		'table' => 'info_discipline',
		'search' => [
			'ico_id' => [
				'name' => '学院',
				'type' => 'select',
				'list_call' => 'get_college_info'
			],
			'id_time' => [
				'name' => '年级',
				'type' => 'select',
				'list' => array_number_dd(date('Y'), 1998, true)
			]
		],
		'filed' => [
			'id_id' => [
				'name' => '编号',
				'type' => 'text',
				'vt' => 'text',
				'pk' => 1,
				'rule' => '/^[0-9]{3,10}$/',
			],
			'id_name' => [
				'name' => '名称',
				'type' => 'text',
				'vt' => 'text',
				'edit' => 1,
				'check' => ['not_empty']
			],
			'id_teacher' => [
				'name' => '系主任',
				'type' => 'text',
				'vt' => 'text',
				'edit' => 1,
				'check' => ['not_empty']
			],
			'id_time' => [
				'name' => '入学年份',
				'type' => 'text',
				'rule' => '/^[0-9]{4}$/',
				'check' => ['not_empty']
			],
			'ico_id' => [
				'name' => '学院',
				'type' => 'select',
				'check_func' => 'check_college_info',
				'select_func' => 'get_college_info',
				'ref_set' => 'ref_college_set',
				'ref_get' => 'ref_college_get_and_campus',
				'out_call' => 'implode_out'
			],
		]
	],
	'class_info' => [
		'name' => '班级信息',
		'table' => 'info_class',
		'ajax' => 'class_info',
		'search' => [
			'ico_id' => [
				'name' => '学院',
				'type' => 'select',
				'list_call' => 'get_college_info'
			],
			'icl_year' => [
				'name' => '年级',
				'type' => 'select',
				'list' => array_number_dd(date('Y'), 1998, true)
			]
		],
		'filed' => [
			'icl_id' => [
				'name' => '编号',
				'type' => 'text',
				'vt' => 'text',
				'pk' => 1,
				'rule' => '/^[0-9]{3,10}$/',
				'check' => ['not_empty']
			],
			'icl_number' => [
				'name' => '班号',
				'type' => 'text',
				'vt' => 'number',
				'rule' => '/^[0-9]{1,2}$/',
				'check' => ['not_empty']
			],
			'ico_id' => [
				'name' => '学院',
				'type' => 'select',
				'check_func' => 'check_college_info',
				'select_func' => 'get_college_info',
				'ref_set' => 'ref_college_set',
				'ref_get' => 'ref_college_get',
			],
			'icl_year' => [
				'name' => '年份',
				'type' => 'select',
				'edit_type' => 'text'
			],
			'id_id' => [
				'name' => '专业',
				'type' => 'select',
				'edit_type' => 'text',
				'edit_value_call' => 'get_class_discipline_value',
				'ref_set' => 'ref_discipline_set',
				'ref_get' => 'ref_discipline_get',
			],
			'icl_teacher' => [
				'name' => '班主任',
				'type' => 'text',
				'vt' => 'text',
				'edit' => 1,
				'check' => ['not_empty']
			],
			'icl_note' => [
				'name' => '备注',
				'type' => 'textarea',
				'edit' => 1,
				'out_call' => 'textarea_out'
			],
		]
	],
	'curriculum_info' => [
		'name' => '课程信息',
		'table' => 'info_curriculum',
		'search' => [
			'ico_id' => [
				'name' => '学院',
				'type' => 'select',
				'list_call' => 'get_college_info'
			],
		],
		'filed' => [
			'cu_id' => [
				'name' => '编号',
				'type' => 'text',
				'vt' => 'text',
				'pk' => 1,
				'rule' => '/^[0-9]{3,10}$/',
				'hide' => 1
			],
			'cu_name' => [
				'name' => '名称',
				'type' => 'text',
				'vt' => 'text',
				'edit' => 1,
				'check' => ['not_empty']
			],
			'cu_point' => [
				'name' => '学分',
				'type' => 'text',
				'vt' => 'number',
				'edit' => 1,
				'check' => [
					'not_empty',
					'is_number'
				]
			],
			'cu_time' => [
				'name' => '学时',
				'type' => 'text',
				'vt' => 'number',
				'edit' => 1,
				'check' => [
					'not_empty',
					'is_number'
				]
			],
			'cu_book' => [
				'name' => '书籍',
				'type' => 'text',
				'vt' => 'text',
				'edit' => 1,
				'check' => ['not_empty']
			],
			'ico_id' => [
				'name' => '学院',
				'type' => 'select',
				'check_func' => 'check_college_info',
				'select_func' => 'get_college_info',
				'ref_set' => 'ref_college_set',
				'ref_get' => 'ref_college_get_and_campus',
				'out_call' => 'implode_out'
			],
			'cu_note' => [
				'name' => '备注',
				'type' => 'textarea',
				'edit' => 1,
				'out_call' => 'textarea_out'
			],
		]
	],
	'teacher_info' => [
		'name' => '教师信息',
		'table' => 'info_teacher',
		'row' => 2,
		'filed' => [
			'it_id' => [
				'name' => '编号',
				'type' => 'text',
				'vt' => 'text',
				'pk' => 1,
				'hide' => 1,
				'rule' => '/^[0-9]{3,10}$/',
				'check' => ['not_empty']
			],
			'it_name' => [
				'name' => '姓名',
				'type' => 'text',
				'vt' => 'text',
				'edit' => 1,
				'check' => ['not_empty']
			],
			'it_start_date' => [
				'name' => '入职日期',
				'type' => 'text',
				'vt' => 'date',
				'edit' => 1,
				'check' => [
					'not_empty',
					'check_date'
				]
			],
			'it_sex' => [
				'name' => '性别',
				'type' => 'radio',
				'edit' => 1,
				'radio' => [
					'男' => '男',
					'女' => '女'
				]
			],
			'it_marry' => [
				'name' => '婚姻状况',
				'type' => 'radio',
				'edit' => 1,
				'radio' => [
					'已婚' => '已婚',
					'未婚' => '未婚'
				]
			],
			'it_edu' => [
				'name' => '学历',
				'type' => 'select',
				'edit' => 1,
				'select_list' => [
					'博士' => '博士',
					'研究生' => '研究生',
					'博士后' => '博士后',
					'博士后及以上' => '博士后及以上',
					'本科' => '本科',
					'大专' => '大专',
					'高职' => '高职',
					'高中' => '高中',
					'中学' => '中学',
					'小学' => '小学',
					'其他' => '其他'
				]
			],
			'it_birthday' => [
				'name' => '生日',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'date',
			],
			'it_tel' => [
				'name' => '电话',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'number',
				'check' => ['is_tel']
			],
			'it_email' => [
				'name' => '邮箱',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'text',
				'check' => ['is_email']
			],
			'it_address' => [
				'name' => '地址',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'text',
			],
			'it_id_card' => [
				'name' => '身份证',
				'type' => 'text',
				'edit' => 1,
				'rule' => '/^[1-9]{1}[0-9]{17}$/',
				'vt' => 'text',
			],
			'it_password' => [
				'name' => '登录密码',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'password',
				'no_out' => 1
			],
			'it_note' => [
				'name' => '备注',
				'type' => 'textarea',
				'edit' => 1,
				'out_call' => 'textarea_out'
			],
		]
	],
	'student_info' => [
		'name' => '学生信息',
		'table' => 'info_student',
		'ajax' => [
			'student_info',
			'city'
		],
		'edit_ajax' => [
			'city',
			'student_edit'
		],
		'search' => [
			'ico_id' => [
				'name' => '学院',
				'type' => 'select',
				'list_call' => 'get_college_info'
			],
			'ic_name' => [
				'name' => '校区',
				'type' => 'select',
				'list_call' => 'get_campus_info'
			],
			'is_grade' => [
				'name' => '年级',
				'type' => 'select',
				'list' => array_number_dd(date('Y'), 1998, true)
			]
		],
		'list_style' => 'width:2500px',
		'row' => 3,
		'filed' => [
			'is_id' => [
				'name' => '学号',
				'type' => 'text',
				'pk' => 1,
				'vt' => 'text',
				'rule' => '/^[1-9]{1}[0-9]{9}$/',
			],
			'is_name' => [
				'name' => '姓名',
				'type' => 'text',
				'vt' => 'text',
				'edit' => 1,
				'check' => ['not_empty']
			],
			'is_hometown' => [
				'name' => '籍贯',
				'type' => 'text',
				'vt' => 'text',
				'edit' => 1,
				'check' => ['not_empty']
			],
			'is_sex' => [
				'name' => '性别',
				'type' => 'radio',
				'edit' => 1,
				'radio' => [
					'男' => '男',
					'女' => '女'
				]
			],
			'is_birthday' => [
				'name' => '生日',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'date',
				'check' => ['is_date']
			],
			'is_province' => [
				'name' => '省份',
				'type' => 'select',
				'edit' => 1,
			],
			'is_city' => [
				'name' => '市',
				'edit' => 1,
				'type' => 'select',
			],
			'is_county' => [
				'name' => '县',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'text',
			],
			'is_zone' => [
				'name' => '区',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'text',
			],
			'is_address' => [
				'name' => '详细地址',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'text',
			],
			'is_id_card' => [
				'name' => '身份证',
				'type' => 'text',
				'vt' => 'text',
				'edit' => 1,
				'rule' => '/^[1-9]{1}[0-9]{17}$/',
			],
			'is_politics' => [
				'name' => '政治面貌',
				'type' => 'select',
				'edit' => 1,
				'select_list' => [
					'群众' => '群众',
					'党员' => '党员',
					'中共团员' => '中共团员',
					'预备党员' => '预备党员'
				]
			],
			'ic_name' => [
				'name' => '校区',
				'type' => 'select',
				'check_func' => 'check_campus_info',
				'select_func' => 'get_campus_info'
			],
			'ico_id' => [
				'name' => '学院',
				'type' => 'select',
				'edit_type' => 'text',
				'edit_value_call' => 'get_college_value',
				'check_func' => 'check_college_info',
				'ref_set' => 'ref_college_set',
				'ref_get' => 'ref_college_get',
			],
			'is_grade' => [
				'name' => '年级',
				'type' => 'select',
				'edit_type' => 'text'
			],
			'id_id' => [
				'name' => '专业',
				'type' => 'select',
				'edit_type' => 'text',
				'edit_value_call' => 'get_class_discipline_value',
				'ref_set' => 'ref_discipline_set',
				'ref_get' => 'ref_discipline_get',
			],
			'icl_id' => [
				'name' => '班级',
				'type' => 'select',
				'edit_type' => 'text',
				'edit_value_call' => 'get_class_info_value',
			],
			'is_password' => [
				'name' => '密码',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'password',
				'no_out' => 1
			],
			'is_email' => [
				'name' => '邮箱',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'email',
				'check' => ['is_email']
			],
			'is_tel' => [
				'name' => '电话',
				'edit' => 1,
				'type' => 'text',
				'vt' => 'text',
				'check' => ['is_tel']
			],
			'is_room' => [
				'name' => '宿舍区',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'text',
			],
			'is_room_number' => [
				'name' => '宿舍号',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'text',
			],
			'is_study_date' => [
				'name' => '入学日期',
				'type' => 'text',
				'edit' => 1,
				'vt' => 'date',
				'check' => ['is_date']
			],
		]
	],
	'curriculum_m' => [
		'name' => '课程安排',
		'table' => 'mg_curriculum',
		'ajax' => 'curriculum_m',
		'search' => [
			'ico_id' => [
				'name' => '学院',
				'type' => 'select',
				'list_call' => 'get_college_info'
			],
			'mc_year' => [
				'name' => '学年',
				'type' => 'select',
				'list' => array_number_dd(date('Y'), 1998, true)
			],
			'mc_grade' => [
				'name' => '年级',
				'type' => 'select',
				'list' => array_number_dd(date('Y'), 1998, true)
			],
			'mc_number' => [
				'name' => '学期',
				'type' => 'select',
				'list' => [
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4
				]
			],
		],

		'filed' => [
			'mc_id' => [
				'name' => '编号',
				'type' => 'text',
				'vt' => 'text',
				'hide' => 1,
				'pk' => 1,
				'rule' => '/^[0-9]{3,10}$/',
				'check' => ['not_empty']
			],
			'it_id' => [
				'name' => '教师ID',
				'type' => 'text',
				'rule' => '/^[1-9]{1}[0-9]*$/',
				'ref_set' => 'ref_teacher_set',
				'ref_get' => 'ref_teacher_get',
				'edit' => 1
			],
			'mc_year' => [
				'name' => '学年',
				'type' => 'text',
				'vt' => 'text',
				'def' => date("Y"),
				'rule' => '/^20[0-9]{2}$/',
			],
			'mc_number' => [
				'name' => '学期',
				'type' => 'select',
				'edit_type' => 'text',
				'select_list' => [
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4
				],
				'rule' => '/^[1-4]{1}$/',
			],
			'ico_id' => [
				'name' => '学院',
				'type' => 'select',
				'check_func' => 'check_college_info',
				'select_func' => 'get_college_info',
				'ref_set' => 'ref_college_set',
				'ref_get' => 'ref_college_get_and_campus',
				'out_call' => 'implode_out',
				'check' => ['not_empty']
			],
			'mc_grade' => [
				'name' => '年级',
				'type' => 'select',
				'edit_type' => 'text',
				'check' => ['not_empty']
			],
			'id_id' => [
				'name' => '专业',
				'type' => 'select',
				'edit_type' => 'text',
				'edit_value_call' => 'get_class_discipline_value',
				'ref_set' => 'ref_discipline_set',
				'ref_get' => 'ref_discipline_get',
				'check' => ['not_empty']
			],
			'cu_id' => [
				'name' => '课程',
				'type' => 'select',
				'edit_type' => 'text',
				'edit_value_call' => 'get_class_curriculum_value',
				'ref_set' => 'ref_curriculum_set',
				'ref_get' => 'ref_curriculum_get',
				'check' => ['not_empty']
			],
			'mc_note' => [
				'name' => '备注',
				'type' => 'textarea',
				'edit' => 1
			]
		]
	]
];