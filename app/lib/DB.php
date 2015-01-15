<?php
/**
 * User: loveyu
 * Date: 2015/1/8
 * Time: 23:23
 */

namespace ULib;


use CLib\Sql;

class DB{

	/**
	 * @var Sql
	 */
	private $driver;

	function __construct(){
		c_lib()->load('sql');
		$this->driver = new Sql(cfg()->get('database'));
		if(!$this->driver->status()){
			define('HAS_RUN_ERROR', true);
			cfg()->set('HAS_RUN_ERROR', $this->driver->ex_message());
		}
	}

	/**
	 * @return Sql
	 */
	public function getDriver(){
		return $this->driver;
	}


	public function get_error(){
		return $this->driver->error();
	}

	public function get_admin_info($name){
		return $this->driver->get("admin", "*", ['a_name' => $name]);
	}

	public function get_admin_info_by_id($id){
		return $this->driver->get("admin", "*", ['a_id' => $id]);
	}

	public function get_student_info_by_id($is_id){
		return $this->driver->get("info_student", "*", ['is_id' => $is_id]);
	}

	public function get_teacher_info_by_id($it_id){
		return $this->driver->get("info_teacher", "*", ['it_id' => $it_id]);
	}

	public function check_permission_exists($name){
		return $this->driver->has("permission", ['p_name' => $name]);
	}

	public function check_permission_exists_by_id($id){
		return $this->driver->has("permission", ['p_id' => $id]);
	}

	public function permission_add($info){
		return $this->driver->insert("permission", $info);
	}

	public function get_permission_list(){
		return $this->driver->select("permission", "*");
	}

	public function update_permission($name, $info){
		return $this->driver->update("permission", $info, ['p_name' => $name]);
	}

	public function update_permission_by_id($id, $info){
		return $this->driver->update("permission", $info, ['p_id' => $id]);
	}

	public function permission_delete($id){
		return $this->driver->delete("permission", ['p_id' => $id]);
	}

	public function permission_get($id){
		return $this->driver->get("permission", "*", ['p_id' => $id]);
	}

	public function get_access($name){
		$role = NULL;
		$read = $this->driver->getReader();
		$p = $read->prepare("SELECT
	role.r_id AS id,
	role.r_name AS `name`,
	role.r_status AS `status`
FROM
	role
INNER JOIN admin ON admin.r_id = role.r_id
WHERE
admin.a_name = :uname");
		if($p->execute([':uname' => $name])){
			$role = $p->fetchAll(\PDO::FETCH_ASSOC)[0];
		}
		return compact('role');
	}

	public function get_access_by_role_id($r_id){
		$role = NULL;
		$read = $this->driver->getReader();
		$p = $read->prepare("select role.r_id as id,role.r_name as name,role.r_status as status from role where role.r_id=:r_id");
		if($p->execute([':r_id' => intval($r_id)])){
			$role = $p->fetchAll(\PDO::FETCH_ASSOC)[0];
		}
		return compact('role');
	}

	public function access_add($info){
		return $this->driver->insert("access", $info);
	}

	public function access_delete($r_id, $p_id){
		return $this->driver->delete("access", ['AND' => compact('r_id', 'p_id')]);
	}

	public function update_user_info($name, $info){
		return $this->driver->update("admin", $info, ['a_name' => $name]);
	}

	public function get_role_list(){
		return $this->driver->select("role", "*");
	}

	public function get_admin_list(){
		return $this->driver->select("admin", ['[>]role' => ['r_id' => 'r_id']], [
			'admin.a_id',
			'admin.a_name',
			'admin.a_status',
			'admin.a_ip',
			'role.r_name',
			'role.r_status',
		], ['ORDER' => 'admin.a_id']);
	}

	public function admin_add($info){
		return $this->driver->insert("admin", $info);
	}

	public function admin_exists_check($name){
		return $this->driver->has("admin", ['a_name' => $name]);
	}

	public function get_role_access($role){
		return $this->driver->select("access", "*", ['r_id' => $role]);
	}

	public function get_role_access_and_name($role){
		return $this->driver->select("access", ['[>]permission' => ['p_id' => 'p_id']], [
			'access.r_id',
			'access.p_id',
			'access.ac_w',
			'access.ac_r',
			'permission.p_name',
			'permission.p_alias'
		], ['r_id' => $role]);
	}

	public function get_admin_allow_access($aid){
		$SQL = <<<SQL
SELECT
	ac_r as r,
	ac_w as w,
	p_name as name
FROM
	access
INNER JOIN role ON role.r_id = access.r_id
INNER JOIN permission ON permission.p_id = access.p_id
INNER JOIN admin ON admin.r_id = role.r_id
WHERE
	admin.a_id = :aid
AND admin.a_status = 0
AND r_status = 0;
SQL;
		$stmt = $this->driver->getReader()->prepare($SQL);
		$stmt->bindValue(":aid", $aid);
		if($stmt->execute()){
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		return false;
	}

	public function get_role_allow_access($rid){
		$SQL = <<<SQL
SELECT
	ac_r as r,
	ac_w as w,
	p_name as name
FROM
	access
INNER JOIN role ON role.r_id = access.r_id
INNER JOIN permission ON permission.p_id = access.p_id
WHERE
	role.r_id = :rid
AND r_status = 0;
SQL;
		$stmt = $this->driver->getReader()->prepare($SQL);
		$stmt->bindValue(":rid", $rid);
		if($stmt->execute()){
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		return false;
	}

	public function check_mc_id_is_teacher($mc_id, $it_id){
		return $this->driver->has("mg_curriculum", ['AND' => compact('mc_id', 'it_id')]);
	}

	public function get_scores_student_list($mc_id){
		$stmt = $this->driver->query("SELECT
	`scores`.`mc_id`,
	`scores`.`is_id`,
	`scores`.`sc_work`,
	`scores`.`sc_test`,
	`scores`.`sc_total`,
	`info_student`.`is_name`,
	`info_class`.`icl_number`,
	`info_discipline`.`id_name`
FROM
	`scores`
INNER JOIN `info_student` ON `scores`.`is_id` = `info_student`.`is_id`
INNER JOIN `info_class` ON `info_class`.`icl_id` = `info_student`.`icl_id`
INNER JOIN `info_discipline` ON `info_discipline`.`id_id` = `info_student`.`id_id`
WHERE
	`mc_id` = " . intval($mc_id));
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function get_curriculum_info_by_mc_id($mc_id){
		$stmt = $this->driver->query("SELECT
	cu_name,
	ic_name,
	ico_name,
	id_name,
	mc_grade,
	mc_number,
	mc_id,
	mc_year
FROM
	mg_curriculum
INNER JOIN info_curriculum ON mg_curriculum.cu_id = info_curriculum.cu_id
INNER JOIN info_discipline ON mg_curriculum.id_id = info_discipline.id_id
INNER JOIN info_college ON mg_curriculum.ico_id = info_college.ico_id
WHERE
	mc_id = " . intval($mc_id));
		$rt = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		return isset($rt[0]) ? $rt[0] : NULL;
	}

	public function student_scores($is_id, $info = []){
		$sql = <<<SQL
SELECT
	scores.*, mg_curriculum.mc_year,
	mg_curriculum.mc_number,
	info_teacher.it_id,
	info_teacher.it_name,
	info_curriculum.cu_id,
	info_curriculum.cu_name,
	info_curriculum.cu_point,
	info_curriculum.cu_time
FROM
	scores
INNER JOIN mg_curriculum ON mg_curriculum.mc_id = scores.mc_id
INNER JOIN info_curriculum ON mg_curriculum.cu_id = info_curriculum.cu_id
INNER JOIN info_teacher ON info_teacher.it_id = mg_curriculum.it_id
WHERE
	is_id = :is_id
SQL;
		$r = [];
		foreach($info as $n => $v){
			$sql .= " AND $n=:$n";
			$r[":{$n}"] = $v;
		}
		$r[':is_id'] = $is_id;
		$stmt = $this->driver->getReader()->prepare($sql);
		if($stmt->execute($r)){
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		return false;
	}

	public function teacher_curriculum($it_id, $info = []){
		$sql = <<<SQL
SELECT
	mg_curriculum.mc_id,
	mg_curriculum.mc_grade,
	mg_curriculum.mc_number,
	mg_curriculum.mc_year,
	info_curriculum.cu_name,
	info_curriculum.cu_point,
	info_curriculum.cu_time,
	info_curriculum.cu_book,
	info_college.ico_name,
	info_discipline.id_name,
	info_college.ic_name
FROM
	mg_curriculum
INNER JOIN info_curriculum ON info_curriculum.cu_id = mg_curriculum.cu_id
INNER JOIN info_college ON info_college.ico_id = mg_curriculum.ico_id
INNER JOIN info_discipline ON info_discipline.id_id = mg_curriculum.id_id
WHERE
	it_id = :it_id
SQL;
		$r = [];
		foreach($info as $n => $v){
			$sql .= " AND $n=:$n";
			$r[":{$n}"] = $v;
		}
		$r[':it_id'] = $it_id;
		$stmt = $this->driver->getReader()->prepare($sql);
		if($stmt->execute($r)){
			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}
		return false;
	}

	public function base_info_insert($table, $info){
		return $this->driver->insert($table, $info);
	}

	public function base_info_delete($table, $info){
		return $this->driver->delete($table, (count($info) > 1) ? ['AND' => $info] : $info);
	}

	public function base_info_get($table, $filed, $where){
		return $this->driver->get($table, $filed, (count($where) > 1) ? ['AND' => $where] : $where);
	}

	public function base_info_edit($table, $filed, $where){
		return $this->driver->update($table, $filed, (count($where) > 1) ? ['AND' => $where] : $where);
	}

	public function get_campus_list(){
		return $this->driver->select('info_campus', "*");
	}

	public function get_college_simple_list(){
		return $this->driver->select('info_college', [
			'ico_id',
			'ico_name',
			'ic_name'
		]);
	}

	/**
	 * 为对应专业和课程插入数据
	 * @param $id_id
	 * @param $mc_id
	 * @return bool|int
	 */
	public function insert_mc_id_list($id_id, $mc_id){
		$stmt = $this->driver->getWriter()->prepare("INSERT INTO scores(mc_id,is_id)
	(
		SELECT
			mg_curriculum.mc_id,
			info_student.is_id
		FROM
			mg_curriculum
		INNER JOIN info_student ON mg_curriculum.id_id = info_student.id_id
		WHERE
			mg_curriculum.id_id = :id_id
		AND mg_curriculum.mc_id = :mc_id
	)");
		if($stmt->execute([
			':id_id' => $id_id,
			':mc_id' => $mc_id
		])
		){
			$r = $stmt->rowCount();
		} else{
			$r = false;
		}
		$stmt->closeCursor();
		return $r;
	}

	/**
	 * 为对应专业和课程插入数据
	 * @param $id_id
	 * @param $mc_id
	 * @param $icl_id
	 * @return bool|int
	 */
	public function insert_mc_id_class_list($id_id, $mc_id, $icl_id){
		$stmt = $this->driver->getWriter()->prepare("INSERT INTO scores (mc_id, is_id)(
	SELECT
		mg_curriculum.mc_id,
		info_student.is_id
	FROM
		info_class
	INNER JOIN info_student ON info_class.icl_id = info_student.icl_id
	INNER JOIN mg_curriculum ON info_class.id_id =mg_curriculum.id_id
	WHERE
		mg_curriculum.id_id = :id_id
	AND mg_curriculum.mc_id = :mc_id
	AND info_class.icl_id = :icl_id
)");
		if($stmt->execute([
			':id_id' => $id_id,
			':mc_id' => $mc_id,
			':icl_id' => $icl_id,
		])
		){
			$r = $stmt->rowCount();
		} else{
			$r = false;
		}
		$stmt->closeCursor();
		return $r;
	}

	public function check_campus_name($name){
		return $this->driver->has('info_campus', ['ic_name' => $name]);
	}

	public function check_college_id($id){
		return $this->driver->has('info_college', ['ico_id' => $id]);
	}


	/**
	 * 检测某一专业是否有某一课程
	 * @param $id_id
	 * @param $mc_id
	 * @return bool
	 */
	public function check_mg_id_exists($id_id, $mc_id){
		return $this->driver->has("mg_curriculum", [
			'AND' => [
				'id_id' => $id_id,
				'mc_id' => $mc_id
			]
		]);
	}

	public function check_class_exists($id_id, $icl_id){
		return $this->driver->has("info_class", [
			'AND' => [
				'id_id' => $id_id,
				'icl_id' => $icl_id
			]
		]);
	}

	public function get_college_names($ids){
		return $this->driver->select("info_college", [
			'ico_id',
			'ico_name'
		], ['ico_id' => $ids]);
	}

	public function get_student_names($ids){
		return $this->driver->select("info_student", [
			'is_id',
			'is_name'
		], ['is_id' => $ids]);
	}

	public function get_class_info($ids){
		return $this->driver->select("info_class", "*", ['icl_id' => $ids]);
	}

	public function get_college_names_and_campus($ids){
		return $this->driver->select("info_college", ['[>]info_campus' => ['ic_name' => 'ic_name']], [
			'info_college.ico_id',
			'info_college.ico_name',
			'info_campus.ic_name',
		], ['ico_id' => $ids]);
	}

	public function get_curriculum_names_by_mc_id($ids){
		return $this->driver->select("mg_curriculum", ['[>]info_curriculum' => ['cu_id' => 'cu_id']], [
			'mg_curriculum.mc_id',
			'info_curriculum.cu_name',
		], ['mc_id' => $ids]);
	}

	public function get_discipline_names($ids){
		return $this->driver->select("info_discipline", [
			'id_id',
			'id_name'
		], ['id_id' => $ids]);
	}

	public function get_teacher_names($ids){
		return $this->driver->select("info_teacher", [
			'it_id',
			'it_name'
		], ['it_id' => $ids]);
	}

	public function get_curriculum_names($ids){
		return $this->driver->select("info_curriculum", [
			'cu_id',
			'cu_name'
		], ['cu_id' => $ids]);
	}

	public function get_role_info($id){
		return $this->driver->get("role", "*", ['r_id' => $id]);
	}

	public function update_admin_info($id, $info){
		return $this->driver->update("admin", $info, ['a_id' => $id]);
	}

	public function role_exists_check($name){
		return $this->driver->has("role", ['r_name' => $name]);
	}

	public function role_id_exists_check($id){
		return $this->driver->has("role", ['r_id' => $id]);
	}

	public function role_add($info){
		return $this->driver->insert("role", $info);
	}

	public function role_delete($id){
		return $this->driver->delete("role", ['r_id' => $id]);
	}

	public function admin_delete($id){
		return $this->driver->delete("admin", ['a_id' => $id]);
	}


	public function role_edit($id, $name, $status){
		return $this->driver->update("role", [
			'r_name' => $name,
			'r_status' => $status
		], ['r_id' => $id]);
	}

	public function rely_admin($id){
		return false;
	}

	public function rely_permission($id){
		return $this->driver->has("access", ['p_id' => $id]);
	}

	public function rely_role($id){
		return $this->driver->has("access", ['r_id' => $id]) || $this->driver->has("admin", ['r_id' => $id]);
	}
}