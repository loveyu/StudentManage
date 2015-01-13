<?php
/**
 * User: loveyu
 * Date: 2015/1/11
 * Time: 10:57
 */

namespace ULib;


class QueryList{
	private $p;
	private $n;
	private $number_all = 0;
	private $order_by = '';
	private $sort = 'ASC';
	private $all_page = 0;
	private $first = 0;
	private $table = "";
	private $filed = [];
	private $data = [];
	private $where = [];
	private $data_call = [];
	private $search = [];

	function __construct(){
		$req = req();
		$this->p = $req->get('p');

		if($this->p <= 0){
			$this->p = 1;
		}
		$this->n = $req->get('n');

		if($this->n < 10){
			$this->n = 20;
		}
		if(!isset($_GET['n'])){
			$this->search['n'] = $this->n;
		}
		if(isset($_GET['sort'])){
			$this->sort = (strtolower($req->get('sort')) == "asc") ? "ASC" : "DESC";
			$this->search['sort'] = $this->sort;
		}
		if(isset($_GET['order'])){
			$this->order_by = $req->get('order');
			$this->search['order'] = $this->order_by;
		}
	}

	/**
	 * @return array
	 */
	public function getData(){
		return $this->data;
	}


	public function search_value($name){
		return req()->get($name);
	}

	/**
	 * @return int
	 */
	public function getP(){
		return $this->p;
	}

	/**
	 * @return int
	 */
	public function getN(){
		return $this->n;
	}

	/**
	 * @return int
	 */
	public function getNumberAll(){
		return $this->number_all;
	}

	/**
	 * @return int
	 */
	public function getAllPage(){
		return $this->all_page;
	}

	/**
	 * @return int
	 */
	public function getFirst(){
		return $this->first;
	}

	/**
	 * @return string
	 */
	public function getTable(){
		return $this->table;
	}

	/**
	 * @return string
	 */
	public function getSort(){
		return $this->sort;
	}

	/**
	 * @return array|null|string
	 */
	public function getOrderBy(){
		return $this->order_by;
	}

	/**
	 * @return array
	 */
	public function getFiled(){
		return $this->filed;
	}


	public function has_data(){
		return isset($this->data[0]);
	}

	public function setBaseInfo($info){
		$this->table = $info['info']['table'];
		foreach($info['info']['filed'] as $name => $v){
			$this->filed[$name] = $v['name'];
			if(isset($v['ref_set']) && isset($v['ref_get'])){
				$this->data_call[$name] = [
					'set' => $v['ref_set'],
					'get' => $v['ref_get'],
					'implode' => isset($v['ref_implode']) && $v['ref_implode']
				];
			}
		}
		if(isset($info['info']['search'])){
			$search = [];
			$req = req();
			foreach($info['info']['search'] as $n => $v){
				if(isset($_GET[$n]) && $_GET[$n] !== ""){
					if(isset($v['like']) && $v['like']){
						if(!isset($search['LIKE'])){
							$search['LIKE'] = [];
						}
						$search['LIKE'][$n] = $req->get($n);
					} else{
						$search[$n] = $req->get($n);
					}
					$this->search[$n] = $req->get($n);
				}
			}
			if(count($search) > 1){
				$this->where = ['AND' => $search];
			} elseif(count($search) == 1){
				$this->where = $search;
			}
		}
		$this->get_list();
	}

	private function get_list(){
		$db = db_class()->getDriver();
		$p = ['LIMIT' => $this->getLimit()];
		if(!empty($this->order_by)){
			$p['ORDER'] = $this->order_by . " " . $this->sort;
		}
		$where = array_merge($this->where, $p);
		$data = $db->select($this->table, array_keys($this->filed), $where);
		foreach($this->data_call as $name => $v){
			for($i = 0; $i < count($data); $i++){
				call_user_func($v['set'], $data[$i][$name]);
			}
			$list = call_user_func($v['get']);
			for($i = 0; $i < count($data); $i++){
				if(isset($list[$data[$i][$name]])){
					if($v['implode'] && !is_array($list[$data[$i][$name]])){
						$data[$i][$name] = "[{$data[$i][$name]}]" . $list[$data[$i][$name]];
					} else{
						$data[$i][$name] = $list[$data[$i][$name]];
					}
				}
			}
		}
		$this->data = $data;
	}

	private function getLimit(){
		$this->number_all = db_class()->getDriver()->count($this->table, $this->where);
		$this->first = $this->n * ($this->p - 1);
		$this->all_page = ceil($this->number_all / $this->n);
		return [
			$this->first,
			$this->n
		];
	}

	public function get_page_nav(){
		$rt = "";
		if($this->all_page > 1){
			$s = "";
			$last = NULL;
			foreach($this->get_nav_list() as $v){
				if($last !== NULL && $last != ($v - 1)){
					$s .= "<li class=\"disabled\"><a href='#'> ...</a></li>";
				}
				$last = $v;
				$c = $v == $this->p ? " class=\"active\"" : "";
				$s .= "<li{$c}><a href=\"" . $this->build_url($v) . "\">{$v}</a></li>";
			}
			$pg_nvm = html_option([
				10 => "10条/页",
				20 => "20条/页",
				50 => "50条/页",
				80 => "80条/页",
				120 => "120条/页",
			], $this->getN());
			$rt .= <<<HTML
<nav>
  <ul class="pagination">
  <li class="disabled"><a href="#">共{$this->number_all}</a></li>
  {$s}
  <li class="disabled"><a href="#" onclick="return false;"><select id="PageNavChange">$pg_nvm</select></a></li>
  </ul>
</nav>
HTML;
		}
		return $rt;
	}

	private function build_url($i){
		$list = $this->search;
		$list['p'] = $i;
		$list['n'] = $this->n;
		$rt = [];
		foreach($list as $n => $v){
			$rt[] = $n . "=" . urlencode($v);
		}
		return get_url(u()->getUriInfo()->getUrlList()) . "?" . implode("&", $rt);
	}

	private function get_nav_list(){
		$i = 1;
		$rt[] = 1;
		for($i = (($this->p > ($i + 5)) ? ($this->p - 5) : 2); $i <= $this->p + 5 && $i <= $this->all_page; $i++){
			$rt[] = $i;
		}
		if(!in_array($this->all_page, $rt)){
			$rt[] = $this->all_page;
		}
		return $rt;
	}
}