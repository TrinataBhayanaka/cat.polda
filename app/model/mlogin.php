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

    function getId($id)
    {
        $sql = "SELECT id_soal FROM master_soal WHERE id_kategori = {$id}";
        $data = $this->fetch($sql,1);

        foreach ($data as $key => $value) {
            $numbers[$key] = $value['id_soal'];
        }

        return $numbers;
    }

    function insert_data($data,$table)
    {
        $this->insert($data,$table);

        return true;
    }

    function delete_data($id,$table)
    {
        $sql = "DELETE FROM {$table} WHERE id_kategori = {$id}";
        $this->query($sql);

        return true;
    }
}
?>
