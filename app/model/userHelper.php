<?php
class userHelper extends Database {
	
    function __construct()
    {   
        global $CONFIG;
        parent::__construct();
        $loadSession = new Session();
        $getUserData = $loadSession->get_session();
        $this->user = $getUserData[0];
        $this->salt = $CONFIG['default']['salt'];
        $this->prefix = "";
        $this->date = date('Y-m-d H:i:s');
        $this->token = str_shuffle('1q2w3e4r5t6y7u8i9o0pazsxdcfvgbhnjmkl');
    }
    
    
    function logoutUser()
    {


        $sql = array(
                'table'=>"bsn_users",
                'field'=>"is_online = 0",
                'condition'=>"idUser = '{$this->user['idUser']}'",
                );

        $result = $this->lazyQuery($sql,$debug,2);
        if ($result) return true;
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
}
?>