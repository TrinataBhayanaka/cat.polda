<?php
class contentHelper extends Database {
	
	var $prefix = "lelang";

	function tracking($postTracking,$type,$start,$end){
// pr($_POST);
        if($type=='1'){
        	$query = "SELECT P.*,Usr.name,CONVERT(VARCHAR(19),P.tanggal,106) AS tanggalformat FROM bsn_pengaduan as P join bsn_users as Usr on P.idUser=Usr.idUser WHERE P.judul LIKE '%{$postTracking}%' AND (P.tanggal BETWEEN '{$start}' AND '{$end}')";
    	}elseif($type=='2'){
    		$query = "SELECT P.*,Usr.name,CONVERT(VARCHAR(19),P.tanggal,106) AS tanggalformat FROM bsn_pengaduan as P join bsn_users as Usr on P.idUser=Usr.idUser WHERE P.ruangLingkup LIKE '%{$postTracking}%' AND (P.tanggal BETWEEN '{$start}' AND '{$end}')";
    	}elseif($type=='3'){
    		$query = "SELECT P.*,Usr.name,CONVERT(VARCHAR(19),P.tanggal,106) AS tanggalformat FROM bsn_pengaduan as P join bsn_users as Usr on P.idUser=Usr.idUser WHERE P.disposisi LIKE '%{$postTracking}%' AND (P.tanggal BETWEEN '{$start}' AND '{$end}')";
    	}elseif($type=='4'){
    		
    	}elseif($type=='5'){
    		$query = "SELECT P.*,Usr.name,CONVERT(VARCHAR(19),P.tanggal,106) AS tanggalformat FROM bsn_pengaduan as P join bsn_users as Usr on P.idUser=Usr.idUser WHERE P.status='{$postTracking}' AND (P.tanggal BETWEEN '{$start}' AND '{$end}')";
    	}elseif($type=='6'){

    		 $query = "SELECT P.*,Usr.name,CONVERT(VARCHAR(19),P.tanggal,106) AS tanggalformat FROM bsn_pengaduan as P join bsn_users as Usr on P.idUser=Usr.idUser WHERE (Usr.email='{$postTracking}' OR Usr.name LIKE '%{$postTracking}%') AND (P.tanggal BETWEEN '{$start}' AND '{$end}')";	
    	}

        $result = $this->fetch($query,1); 

        
        // pr($query);
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
    function updContent($id){
		$query = "UPDATE bsn_content
						SET 
							description = '{$_POST['content']}'
						WHERE
							id = '{$id}'";
		// echo $query;					
		$result = $this->query($query);					
	}	
	function getData()
	{
		$sql = "SELECT * FROM code_activity";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}
	
	function getMessage()
	{
		$sql = "SELECT m.*, um.name,um.email FROM my_message m LEFT JOIN user_member um ON m.receive = um.id ";
		$res = $this->fetch($sql,1);
		if ($res) return $res;
		return false;
	}
	
	function saveMessage($data)
	{
		foreach ($data as $key => $val){
			$tmpfield[] = $key;
			$tmpdata[] = "'$val'";
		}
		// from,to,subject,content,createdate,n_status
		$tmpfield[] = 'fromwho';
		$tmpfield[] = 'createdate';
		$tmpfield[] = 'n_status';
		
		$date = date('Y-m-d H:i:s');
		$tmpdata[] = 0;
		$tmpdata[] = "'{$date}'";
		$tmpdata[] = 1;
		
		$field = implode(',',$tmpfield);
		$data = implode(',',$tmpdata);
		
		$sql = "INSERT INTO my_message ({$field}) 
				VALUES ({$data})";
		// pr($sql);
		// exit;
		$res = $this->query($sql);
		if ($res) return true;
		return false;
	}
	
	function get_user($data)
	{
		$query = "SELECT * FROM user_member WHERE email = '{$data}'";
		
		$result = $this->fetch($query,1);
		
		return $result;
	}
	
	function importData($name=null)
	{
		$query = "INSERT INTO import (name,n_status) VALUES ('{$name}', 1)";
		// pr($query);
		$result = $this->query($query);
		
		return $result;
	}
	
	function getRegistrant($n_status=1, $debug=0)
	{
		$filter = "";
		$sql = array(
                'table'=>"user",
                'field'=>"COUNT(1) AS total",
                'condition' => "n_status IN ({$n_status}) {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res) return $res;
		return false;
	}

	function getCourse($n_status=1, $debug=0)
	{
		$filter = "";
		$sql = array(
                'table'=>"kursus",
                'field'=>"COUNT(1) AS total",
                'condition' => "n_status IN ({$n_status}) {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res) return $res;
		return false;
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

	function getDownloadEbook($n_status=1,$debug=0)
	{
		$filter = "";
		$sql = array(
                'table'=>"file",
                'field'=>"SUM(downloadCount) AS total",
                'condition' => "n_status IN ({$n_status}) {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res) return $res;
		return false;
	}

	function quizStatistic()
	{
		$sql = array(
                'table'=>"nilai AS n, grup_kursus AS gk",
                'field'=>"COUNT(n.idUser) AS total, gk.namagrup",
                'joinmethod'=>'LEFT JOIN',
                'join'=>'n.idGroupKursus = gk.idGrup_kursus',
                'condition' => "n.n_status = 1 {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res) return $res;
        return false;
	}
	function saveData($data=array(), $table="_users", $debug=false)
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
    function simpanData($query,$data=array(), $table="_content", $debug=false)
    {
        // pr($data);
    	$id="id =".$data['id'];
        if ($query==3){
            
        	foreach ($data as $key => $value) {
                $id="id =".$value['id'];
                $data= array('value' =>$value['value']);

                 $run = $this->save("update", "{$this->prefix}{$table}",$data, $id, $debug);

            }
           

        }elseif ($query==2){
            
            $run = $this->save("update", "{$this->prefix}{$table}", $data, $id, $debug);

        }else{
          	// pr($data);
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
        $additional = $data;

        $fetch = $this->fetchSingleTable($table, $condition, $oderby, $additional, $debug);
        if ($fetch) return $fetch;
        return false;
    }

	function delete($table,$condition)
    {
        $sql = "DELETE FROM  {$table} WHERE {$condition}";
        $res = $this->query($sql);

        return $res;
    }

    function updStatus($table,$id,$type,$stat=false){


        $id1="id =".$id." AND type =".$type;
        $id0="id <>".$id." AND type =".$type;
        if($stat==1){
            $query1 = "UPDATE {$this->prefix}{$table}
                        SET 
                            statusPenelaahan = '1'
                        WHERE
                            {$id1}";
                        

            $query2 = "UPDATE {$this->prefix}{$table}
                        SET 
                            statusPenelaahan = '0'
                        WHERE
                            {$id0}";
        }elseif($stat==2){
             $query1 = "UPDATE {$this->prefix}{$table}
                        SET 
                            statusDisposisi = '1'
                        WHERE
                            {$id1}";
                        

            $query2 = "UPDATE {$this->prefix}{$table}
                        SET 
                            statusDisposisi = '0'
                        WHERE
                            {$id0}";
        }elseif($stat==3){
             $query1 = "UPDATE {$this->prefix}{$table}
                        SET 
                            statusTindakLanjut = '1'
                        WHERE
                            {$id1}";
                        

            $query2 = "UPDATE {$this->prefix}{$table}
                        SET 
                            statusTindakLanjut = '0'
                        WHERE
                            {$id0}";

        }else{
            $query1 = "UPDATE {$this->prefix}{$table}
                            SET 
                                n_status = '1'
                            WHERE
                                {$id1}";
                            

            $query2 = "UPDATE {$this->prefix}{$table}
                            SET 
                                n_status = '0'
                            WHERE
                                {$id0}";
        }
        $result1 = $this->query($query1); 
        $result2 = $this->query($query2); 

        if ($result2) return true;
        return false;
    }

    
}
?>