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
        // pr($sql);
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
        $check = $this->insert($data,$table);
        if($check) return true; else return false;
    }

    function update_data($data,$table,$cond)
    {
        $sql = "UPDATE {$table} SET {$data} WHERE {$cond}";
        $this->query($sql);

        return true;
    }

    function delete_data($id,$table)
    {
        $sql = "DELETE FROM {$table} WHERE id_kategori = {$id}";
        $this->query($sql);

        return true;
    }

    function getidUser()
    {
        $sql = "SELECT id_peserta FROM master_peserta";
        $data = $this->fetch($sql,1);

        return $data;
    }

    function delData($table,$cond)
    {
        $sql = "DELETE FROM {$table} WHERE {$cond}";
        $this->query($sql);

        return true;
    }
}
?>
