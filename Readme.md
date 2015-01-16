# 学生信息管理系统
## 功能
### 后台功能
* 用户角色管理
* 用户权限管理
* 管理员管理
* 密码修改

### 信息级管理
* 校区管理
* 学院管理
* 专业管理
* 班级管理
* 课程管理
* 教师信息管理
* 学生信息管理
* 专业课程安排
* 学生选课管理
* 课程分数报表

### 用户级管理
* 学生课程查询
* 教师课程查询
* 教师成绩登入

## 数据库说明
* `data_structure.sql` 为基本数据结构文件，无任何数据信息
* `base_info.sql` 为部分基本信息，移除了学生信息，选课信息，教师信息
* `sm_all_sql.zip` 完整数据，解压后为68M，包含全部信息，含12万学生信息，120万分数信息

### 数据库导入与配置
1. 建立数据库，编码选择`UTF8`,`utf8_general_ci`，这个没得选
2. 导入数据库文件
	* 精简导入：先导入`data_structure.sql`，再导入`base_info.sql`，并在`config/all.php`中修改数据库连接信息。
	* 完整导入：先解压`sm_all_sql.zip`，导入解压后的sql文件。
3. 将web目录设置为主机根目录，并配置相关环境，PHP版本要求大于5.4，Mysql版本要求大于5.5
	* Apache服务器设置，开启Rewrite或者使用PATHINFO模式(即`/index.php/`方式)
	* Nginx服务器,根目录下的nginx.conf对应着配置就好
	* 权限，保证整个服务器目录可读，`App/log`,目录可写
4. 初始化，访问默认域名即可，不在根域名自己看着办。
	* 默认超级管理员：`loveyu`，密码：`123456`
	* 默认信息管理员：`help`，密码：`123456`
	* 学生和教师默认密码均为`123456`
	
### 反馈及演示
* 演示: [http://demo.loveyu.net/StudentManage/](http://demo.loveyu.net/StudentManage/)
* 反馈: [http://www.loveyu.org/3907.html](http://www.loveyu.org/3907.html)