<?php
class loginHelper extends Database {
	
	var $session;
	function __construct()
	{
		parent::__construct();
		$this->session = new Session;
	}

	function goLogin()
	{
		// pr($_POST);exit;
		$username = _p('username');
		$password = _p('password');
		
		// pr($data);		
		

		$sql = "SELECT * FROM {$this->prefix}_users WHERE (username = '{$username}' OR email = '{$username}') AND type IN (1,3) LIMIT 1";

		// pr($sql);exit;
		$res = $this->fetch($sql,0,0);
		// pr($res);
		if ($res){
			$salt = sha1($password.$res['salt']);
			// pr($salt);exit;
			// $getRule = "SELECT * FROM cdc_group_rule WHERE groupID = {$res['usertype']} LIMIT 1 ";
			// $res['rule'] = $this->fetch($getRule);
			// pr($res);
			// exit;
			if ($res['password'] == $salt){
				// $_SESSION['admin'] = $res;
				$this->session->set_session($res);
				return true;
			}
		}		
		
		return false;
	}
	
	function createUser($data)
	{
		
		$result = $this->insert($data,'hr_users');
		
		return $result;
	}
	
	function user_check($user){
		$query = "SELECT count(username) as count FROM user_member WHERE username LIKE '{$user}'";
		
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
}
?>