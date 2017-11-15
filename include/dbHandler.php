<?php

class dbHandler{

	private $dbhost = 'localhost';
	private $dbuser = 'root';
	private $dbpass = '';
	private $dbname = 'api_login';

	function __construct(){
		$pdo = new PDO('mysql:dbname='.$this->dbname, $this->dbuser, $this->dbpass);
		$this->db = new NotOrm($pdo);
	}

	public function validate($api){
		$res = $this->db->tb_user()->where('api_key', $api);
		return $res->fetch();
	}

	public function verifyLogin($user, $pass){
		$res = $this->db->tb_user('username', $user)->where('password', $pass);
		return $res->fetch();
	}

	public function createUser($data){
		$key = $this->generateKey();
		$data['password'] = md5($data['password']);
		$data += ['api_key'=>$key];
		$data += ['role'=>'2'];
		$result = $this->db->tb_user()->insert($data);
		if($result) return true;
		return false;
	}

	public function updateUser($id, $data){
		$result = $this->db->tb_user()->where('id', $id)->update($data);
		if($result) return true;
		return false;
	}

	public function deleteUser($id){
		$result = $this->db->tb_user('id',$id)->delete();
		if($result) return true;
		return false;
	}

	public function deleteUjian($id){
		$result = $this->db->tb_ujian('id_ujian',$id)->delete();
		if($result) return true;
		return false;
	}

	public function getUsers(){
		$result = array();
		foreach($this->db->tb_user as $user){
			$result['all'][] = array(
				'status'	=> true,
				'id_user'	=> $user['id'],
				'username'	=> $user['username'],
				'password'	=> $user['password'],
				'role'		=> $user['role'],
				'api_key'	=> $user['api_key']);
		}
		return $result;
	}

	public function getUserById($id){
		$result = $this->db->tb_user('id',$id);
		return $result->fetch();
	}

	public function createUjian($data){
		$data += ['created_date' => date('Y-m-d')];
		$result = $this->db->tb_ujian()->insert($data);
		if($result) return true;
		return false;
	}

	public function getUjian(){
		$result = array();
		foreach($this->db->tb_ujian as $ujian){
			$result['all'][] = array(
				'status'	=> true,
				'username'	=> $ujian['username'],
				'skor_r'	=> $ujian['skor_r'],
				'skor_i'	=> $ujian['skor_i'],
				'skor_a'	=> $ujian['skor_a'],
				'skor_s'	=> $ujian['skor_s'],
				'skor_e'	=> $ujian['skor_e'],
				'skor_c'	=> $ujian['skor_c'],
				'keterangan'=> $ujian['keterangan'],
				'created_date'=> $ujian['created_date']);
		}
		return $result;
	}

	public function getUjianById($id){
		$result = array();
		foreach($this->db->tb_ujian('id_ujian',$id) as $ujian){
			$result['all'][] = array(
				'status'	=> true,
				'username'	=> $ujian['username'],
				'skor_r'	=> $ujian['skor_r'],
				'skor_i'	=> $ujian['skor_i'],
				'skor_a'	=> $ujian['skor_a'],
				'skor_s'	=> $ujian['skor_s'],
				'skor_e'	=> $ujian['skor_e'],
				'skor_c'	=> $ujian['skor_c'],
				'keterangan'=> $ujian['keterangan'],
				'created_date'=> $ujian['created_date']);
		}
		return $result;
	}

	public function getUjianByUsername($user){
		$result = array();
		foreach($this->db->tb_ujian('username',$user) as $ujian){
			$result['all'][] = array(
				'status'	=> true,
				'username'	=> $ujian['username'],
				'skor_r'	=> $ujian['skor_r'],
				'skor_i'	=> $ujian['skor_i'],
				'skor_a'	=> $ujian['skor_a'],
				'skor_s'	=> $ujian['skor_s'],
				'skor_e'	=> $ujian['skor_e'],
				'skor_c'	=> $ujian['skor_c'],
				'keterangan'=> $ujian['keterangan'],
				'created_date'=> $ujian['created_date']);
		}
		return $result;
	}

	public function resetKey($id_user){
		$result = $this->db->tb_user('id',$id_user)->update(array('api_key'=>''));
		return ($result == true);
	}

	private function generateKey(){
		return md5(uniqid(rand(), true));
	}
}

?>

