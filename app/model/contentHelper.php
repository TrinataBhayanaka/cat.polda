<?php
class contentHelper extends Database {
	
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

    function tracking($postTracking,$iduser){

        $query = "SELECT *,CONVERT(VARCHAR(19),tanggal,106) AS tanggalformat FROM bsn_pengaduan WHERE idUser='$iduser' AND (idPengaduan LIKE '%{$postTracking}%' OR idLaporan LIKE '%{$postTracking}%')";

        $result = $this->fetch($query,1);
        // pr($query);
        if(empty($result)){
           $query = "SELECT P.*, CONVERT(VARCHAR(19),P.tanggal,106) AS tanggalformat FROM bsn_pengaduan as P join {$this->prefix}_users as Usr on P.idUser=Usr.idUser WHERE Usr.email='{$postTracking}'";

           $result = $this->fetch($query,1); 

           return $result;
        }
        // pr($result);
        // exit;
        return $result;

    }
    function getContent($type=1,$category=1){
        $query = "SELECT * FROM bsn_content WHERE type='{$type}' AND category='{$category}' AND n_status='1'";
        // pr($query);
        $result = $this->fetch($query,1);

        return $result;
    }

    
    
    function getOnlineUser($n_status=1, $debug=0)
    {
        $filter = "";
        $sql = array(
                'table'=>"user",
                'field'=>"COUNT(1) AS total",
                'condition' => "n_status IN ({$n_status}) AND is_online = 1 {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res) return $res;
        return false;
    }

    

    function saveData($data=array(), $table="_survey", $debug=0)
    {

        if ($table == "_users"){

            if ($data['id']) $id = " idUser = {$data['id']}";  
            else $id = "";
        } 
        else $id = " id = {$data['id']}";
        if ($id){

            $run = $this->save("update", "{$this->prefix}{$table}", $data, $id, $debug);

        }else{
            $data['createDate'] = date('Y-m-d H:i;s');
            $run = $this->save("insert", "{$this->prefix}{$table}", $data, false, $debug);
    
        }

        if ($run) return true;
        return false;
    }

    function fetchData($data=array(),$debug=false)
    {

        $table = $data['table'];
        $condition = $data['condition'];
        $oderby = $data['oderby'];

        $fetch = $this->fetchSingleTable($table, $condition, $oderby, $debug);
        if ($fetch) return $fetch;
        return false;
    }
    function getStatusEvent(){

        $query="select * from ujian";
        return $event;
    }
}
?>
