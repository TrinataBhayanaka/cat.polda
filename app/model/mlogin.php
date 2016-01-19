<?php
class mlogin extends Database {
	
	var $prefix = "";
	var $salt = "";
	function __construct()
	{
        parent::__construct();
		// $this->db = new Database;
		$this->salt = "ovancop1234";
		$this->token = str_shuffle('cmsaj23y4ywdni237yeisa');
		$this->date = date('Y-m-d H:i:s');

	}

    function getData($table,$all,$id=false)
    {
        if ($id) $cond = "WHERE {$id}"; else $cond = "";
        $sql = "SELECT * FROM {$table} {$cond}";
        
        $data = $this->fetch($sql,$all);

        return $data;
    }
}
?>
