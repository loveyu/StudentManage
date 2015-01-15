/*
Navicat MySQL Data Transfer

Source Server         : loclhost
Source Server Version : 50615
Source Host           : 127.0.0.1:3306
Source Database       : sm

Target Server Type    : MYSQL
Target Server Version : 50615
File Encoding         : 65001

Date: 2015-01-16 01:30:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for access
-- ----------------------------
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `r_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  `p_id` int(10) unsigned NOT NULL COMMENT '权限ID',
  `ac_r` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有读的权限',
  `ac_w` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有写的权限',
  PRIMARY KEY (`r_id`,`p_id`),
  KEY `p_id` (`p_id`),
  CONSTRAINT `access_ibfk_1` FOREIGN KEY (`r_id`) REFERENCES `role` (`r_id`),
  CONSTRAINT `access_ibfk_2` FOREIGN KEY (`p_id`) REFERENCES `permission` (`p_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `a_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `a_name` char(20) NOT NULL COMMENT '用户名',
  `a_pwd` char(64) NOT NULL COMMENT '用户密码值',
  `a_salt` char(32) CHARACTER SET latin1 NOT NULL COMMENT '用户密码salt',
  `r_id` int(255) unsigned NOT NULL DEFAULT '0' COMMENT '用户权限组,0默认权限为空',
  `a_ip` varchar(255) NOT NULL DEFAULT '192.168.*|127.0.0.1' COMMENT '允许登录的IP地址列表',
  `a_status` tinyint(255) unsigned DEFAULT '0' COMMENT '用户状态，0正常，1:限制登录，2解除权限',
  PRIMARY KEY (`a_id`),
  UNIQUE KEY `UNK` (`a_name`),
  KEY `r_id` (`r_id`),
  CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`r_id`) REFERENCES `role` (`r_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for info_campus
-- ----------------------------
DROP TABLE IF EXISTS `info_campus`;
CREATE TABLE `info_campus` (
  `ic_name` varchar(100) DEFAULT NULL COMMENT '校区名称',
  `ic_address` varchar(255) DEFAULT NULL COMMENT '地址',
  `ic_postcode` char(10) DEFAULT NULL COMMENT '邮编',
  `ic_tel` char(20) DEFAULT NULL COMMENT '电话',
  UNIQUE KEY `UNK` (`ic_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for info_class
-- ----------------------------
DROP TABLE IF EXISTS `info_class`;
CREATE TABLE `info_class` (
  `icl_id` char(20) NOT NULL COMMENT '班级ID',
  `ico_id` char(20) NOT NULL COMMENT '学部ID',
  `id_id` char(20) NOT NULL COMMENT '专业ID',
  `icl_number` tinyint(3) unsigned NOT NULL COMMENT '班级序号',
  `icl_teacher` char(20) NOT NULL COMMENT '班主任',
  `icl_note` varchar(1024) DEFAULT NULL COMMENT '备注',
  `icl_year` int(4) DEFAULT NULL COMMENT '开设年份',
  PRIMARY KEY (`icl_id`),
  KEY `ico_id` (`ico_id`),
  KEY `id_id` (`id_id`),
  CONSTRAINT `info_class_ibfk_1` FOREIGN KEY (`ico_id`) REFERENCES `info_college` (`ico_id`),
  CONSTRAINT `info_class_ibfk_2` FOREIGN KEY (`id_id`) REFERENCES `info_discipline` (`id_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for info_college
-- ----------------------------
DROP TABLE IF EXISTS `info_college`;
CREATE TABLE `info_college` (
  `ico_id` char(20) NOT NULL,
  `ico_name` varchar(100) NOT NULL COMMENT '学院名称',
  `ic_name` varchar(100) NOT NULL COMMENT '校区名称',
  `ico_tel` char(20) NOT NULL COMMENT '学院电话',
  `ico_teacher` char(20) NOT NULL COMMENT '主要负责人',
  PRIMARY KEY (`ico_id`),
  UNIQUE KEY `UNK` (`ico_name`,`ic_name`) USING BTREE,
  KEY `ic_name` (`ic_name`),
  CONSTRAINT `info_college_ibfk_1` FOREIGN KEY (`ic_name`) REFERENCES `info_campus` (`ic_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for info_curriculum
-- ----------------------------
DROP TABLE IF EXISTS `info_curriculum`;
CREATE TABLE `info_curriculum` (
  `cu_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '课程ID',
  `cu_name` char(100) NOT NULL COMMENT '课程名',
  `cu_point` float unsigned NOT NULL COMMENT '学分',
  `cu_time` int(10) unsigned NOT NULL COMMENT '学时',
  `cu_book` varchar(128) DEFAULT NULL COMMENT '对应书籍',
  `ico_id` char(20) NOT NULL COMMENT '课程所属学院',
  `cu_note` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`cu_id`),
  KEY `ico_id` (`ico_id`),
  CONSTRAINT `info_curriculum_ibfk_1` FOREIGN KEY (`ico_id`) REFERENCES `info_college` (`ico_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7796 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for info_discipline
-- ----------------------------
DROP TABLE IF EXISTS `info_discipline`;
CREATE TABLE `info_discipline` (
  `id_id` char(20) NOT NULL COMMENT '专业ID',
  `id_name` varchar(50) NOT NULL COMMENT '专业名称',
  `id_teacher` char(10) NOT NULL COMMENT '专业负责人',
  `id_time` int(4) unsigned NOT NULL COMMENT '专业年级',
  `ico_id` char(20) DEFAULT NULL COMMENT '学院ID',
  PRIMARY KEY (`id_id`),
  UNIQUE KEY `unique_id_name` (`id_name`,`id_time`) USING BTREE,
  KEY `ico_id` (`ico_id`),
  CONSTRAINT `info_discipline_ibfk_1` FOREIGN KEY (`ico_id`) REFERENCES `info_college` (`ico_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for info_student
-- ----------------------------
DROP TABLE IF EXISTS `info_student`;
CREATE TABLE `info_student` (
  `is_id` char(10) NOT NULL COMMENT '学号',
  `is_name` char(20) NOT NULL COMMENT '名称',
  `is_hometown` char(20) NOT NULL COMMENT '籍贯',
  `is_sex` char(2) NOT NULL COMMENT '性别',
  `is_birthday` date NOT NULL COMMENT '生日',
  `is_province` varchar(50) NOT NULL COMMENT '省份',
  `is_city` varchar(20) NOT NULL COMMENT '城市',
  `is_county` varchar(20) DEFAULT NULL COMMENT '县',
  `is_zone` varchar(20) DEFAULT NULL COMMENT '地区',
  `is_address` varchar(100) NOT NULL COMMENT '详细管理',
  `is_id_card` char(18) NOT NULL COMMENT '身份证号码',
  `is_politics` varchar(10) NOT NULL COMMENT '政治面貌',
  `ic_name` varchar(100) NOT NULL COMMENT '校区',
  `ico_id` char(20) NOT NULL COMMENT '学院ID',
  `id_id` char(20) NOT NULL COMMENT '专业ID',
  `icl_id` char(20) NOT NULL COMMENT '班级ID',
  `is_password` varchar(40) NOT NULL COMMENT '登录密码',
  `is_email` varchar(128) DEFAULT NULL COMMENT '邮箱',
  `is_tel` char(20) DEFAULT NULL COMMENT '电话',
  `is_room` varchar(128) DEFAULT NULL COMMENT '宿舍',
  `is_room_number` char(10) DEFAULT NULL COMMENT '宿舍号',
  `is_study_date` date NOT NULL COMMENT '入学日期',
  `is_grade` int(4) unsigned NOT NULL COMMENT '年级',
  PRIMARY KEY (`is_id`),
  KEY `ic_name` (`ic_name`),
  KEY `ico_id` (`ico_id`),
  KEY `id_id` (`id_id`),
  KEY `icl_id` (`icl_id`),
  CONSTRAINT `info_student_ibfk_1` FOREIGN KEY (`ic_name`) REFERENCES `info_campus` (`ic_name`),
  CONSTRAINT `info_student_ibfk_2` FOREIGN KEY (`ico_id`) REFERENCES `info_college` (`ico_id`),
  CONSTRAINT `info_student_ibfk_3` FOREIGN KEY (`id_id`) REFERENCES `info_discipline` (`id_id`),
  CONSTRAINT `info_student_ibfk_4` FOREIGN KEY (`icl_id`) REFERENCES `info_class` (`icl_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for info_teacher
-- ----------------------------
DROP TABLE IF EXISTS `info_teacher`;
CREATE TABLE `info_teacher` (
  `it_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '教师ID',
  `it_name` char(20) NOT NULL COMMENT '教师名称',
  `it_start_date` date NOT NULL COMMENT '入职时间',
  `it_sex` char(2) NOT NULL COMMENT '性别',
  `it_birthday` date NOT NULL COMMENT '出生日期',
  `it_marry` char(255) NOT NULL COMMENT '婚姻状况',
  `it_tel` char(20) NOT NULL COMMENT '电话',
  `it_address` varchar(255) NOT NULL COMMENT '住址',
  `it_email` varchar(128) NOT NULL COMMENT '邮箱',
  `it_note` varchar(255) DEFAULT NULL COMMENT '备注',
  `it_id_card` char(18) NOT NULL COMMENT '身份证',
  `it_password` char(40) NOT NULL COMMENT '密码',
  `it_edu` char(20) NOT NULL COMMENT '学历',
  PRIMARY KEY (`it_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10800 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mg_curriculum
-- ----------------------------
DROP TABLE IF EXISTS `mg_curriculum`;
CREATE TABLE `mg_curriculum` (
  `mc_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '课程序号',
  `mc_year` int(4) unsigned NOT NULL COMMENT '年份',
  `mc_number` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '学期',
  `mc_grade` int(4) unsigned NOT NULL COMMENT '专业年级',
  `mc_note` varchar(255) DEFAULT NULL COMMENT '备注',
  `id_id` char(20) NOT NULL COMMENT '专业号',
  `ico_id` char(20) NOT NULL COMMENT '学院',
  `cu_id` int(10) unsigned NOT NULL COMMENT '课程ID',
  `it_id` int(10) unsigned NOT NULL COMMENT '教师ID',
  PRIMARY KEY (`mc_id`),
  KEY `id_id` (`id_id`),
  KEY `ico_id` (`ico_id`),
  KEY `it_id` (`it_id`),
  KEY `cu_id` (`cu_id`),
  CONSTRAINT `mg_curriculum_ibfk_1` FOREIGN KEY (`id_id`) REFERENCES `info_discipline` (`id_id`),
  CONSTRAINT `mg_curriculum_ibfk_2` FOREIGN KEY (`ico_id`) REFERENCES `info_college` (`ico_id`),
  CONSTRAINT `mg_curriculum_ibfk_3` FOREIGN KEY (`it_id`) REFERENCES `info_teacher` (`it_id`),
  CONSTRAINT `mg_curriculum_ibfk_4` FOREIGN KEY (`cu_id`) REFERENCES `info_curriculum` (`cu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51696 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for permission
-- ----------------------------
DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `p_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `p_name` char(20) NOT NULL COMMENT '权限名称',
  `p_alias` varchar(64) NOT NULL COMMENT '权限别名',
  `p_status` tinyint(255) unsigned NOT NULL DEFAULT '0' COMMENT '权限状态，0：有效，1：无效',
  PRIMARY KEY (`p_id`),
  UNIQUE KEY `UKN` (`p_name`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `r_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `r_name` char(20) NOT NULL COMMENT '角色名称',
  `r_status` tinyint(255) unsigned DEFAULT '0' COMMENT '角色状态，0:正常，1:禁用',
  PRIMARY KEY (`r_id`),
  UNIQUE KEY `UNN` (`r_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for scores
-- ----------------------------
DROP TABLE IF EXISTS `scores`;
CREATE TABLE `scores` (
  `is_id` char(10) NOT NULL COMMENT '学号',
  `mc_id` int(10) unsigned NOT NULL COMMENT '课程号',
  `sc_work` float unsigned DEFAULT NULL COMMENT '平时成绩',
  `sc_test` float unsigned DEFAULT NULL COMMENT '考试成绩',
  `sc_total` float unsigned DEFAULT NULL COMMENT '总成绩',
  PRIMARY KEY (`is_id`,`mc_id`),
  KEY `mc_id` (`mc_id`),
  CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`is_id`) REFERENCES `info_student` (`is_id`),
  CONSTRAINT `scores_ibfk_2` FOREIGN KEY (`mc_id`) REFERENCES `mg_curriculum` (`mc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
