<?php
class userHelper extends Database {
	
    function __construct()
    {

        parent::__construct();
        $session = new Session;
        $getSessi = $session->get_session();
        $this->user = $getSessi['ses_user']['login'];
    }


    function getListUser($data=false, $debug=false)
    {

        $id = $data['id'];

        $sql = array(
                    'table' =>'social_member',
                    'field' => "*",
                    'condition' => "id = {$id}",
                    'limit' => 1
                );
        // $sqlu = "UPDATE social_member SET last_login = '{$lastLogin}' ,login_count = {$loginCount} WHERE id = {$res['id']} LIMIT 1";
        $result = $this->lazyQuery($sql);
        if ($result) return $result;
        return false;

    }


    function saveData($data=array(), $table="_users", $debug=false)
    {

        if ($table == "_users"){

            if ($data['id']) $id = " idUser = {$data['id']}";  
            else $id = "";
        }else $id = " id = {$data['id']}";
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