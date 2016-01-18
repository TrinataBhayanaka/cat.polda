<?php 

/* Class Name = Database
 * Variabel Input : query, result, connect, numRec
 * Variabel Input Type : Protected
 * Created By : Ovan Cop
 * Class Desc : Kumpulan fungsi database (db helper)
 */

class Database
{
	protected $var_query = null;
	protected $var_result = null;
	protected $var_connect = null;
	protected $var_numRec = null;
	protected $config = array();
	protected $dbConfig = array();
	protected $keyconfig = null;
	var $link = false;
	
	public function __construct() {
		
		/* nothing here */
		global $dbConfig;
		$this->prefix = $dbConfig[0]['prefix'];
		$this->preftable = $dbConfig[0]['preftable'];
	}
	
	function setAppKey()
	{
		global $CONFIG;
		if (array_key_exists('default',$CONFIG)) $keyconfig = 'default';
		if (array_key_exists('admin',$CONFIG)) $keyconfig = 'admin';
		if (array_key_exists('dashboard',$CONFIG)) $keyconfig = 'dashboard';
		if (array_key_exists('services',$CONFIG))$keyconfig = 'services';
		
		return $keyconfig;
	}
	
	function initialitation()
	{

		$this->cacheTable();
	}

	function setDbConfig()
	{
		global $dbConfig;
		
	}
	
	public function open_connection($dbuse) {
		
		global $dbConfig, $CONFIG;
		
		$this->keyconfig = $this->setAppKey();
		
		if ((is_array($dbConfig)) and ($dbConfig !=''))
		{
			
			if ($dbConfig[$dbuse]['server'] !=''){
				$db_status = 1;
			}else{
				$this->db_error('Server not defined');
				exit;
			}
			
			switch ($dbConfig[$dbuse]['server'])
			{
				case 'mysql':
				{
					
					if ($CONFIG[$this->keyconfig]['app_status'] == 'Production'){
						$connect = @mysql_connect(trim($dbConfig[$dbuse]['host']), $dbConfig[$dbuse]['user'], $dbConfig[$dbuse]['pass']) or die ($this->db_error('Connection error'));
					
					}else{
						$connect = @mysql_connect(trim($dbConfig[$dbuse]['host']), $dbConfig[$dbuse]['user'], $dbConfig[$dbuse]['pass']) or die ($this->db_error('Connection error'));
						
					}
					
					
					if ($connect){
					
						if ($CONFIG[$this->keyconfig]['app_status'] == 'Production'){
							@mysql_select_db(trim($dbConfig[$dbuse]['name']), $connect) or die ($this->db_error('No Database Selected'));	
						
						}else{
							mysql_select_db(trim($dbConfig[$dbuse]['name']),$connect) or die ($this->db_error('No Database Selected'));
							
							// mysql_select_db('florakalbar', $connect) or die ($this->db_error('No Database Selected'));
							
						}
						
						$this->link = $connect;
						return $connect;
					
					}else{
					
						return false;
					}
				}
				break;

				case 'mssql':
				{
					
					if ($CONFIG[$this->keyconfig]['app_status'] == 'Production'){
						$connect = @mssql_connect(trim($dbConfig[$dbuse]['host']), $dbConfig[$dbuse]['user'], $dbConfig[$dbuse]['pass']) or die ($this->db_error('Connection error'));
					
					}else{
						$connect = mssql_connect(trim($dbConfig[$dbuse]['host']), $dbConfig[$dbuse]['user'], $dbConfig[$dbuse]['pass']) or die ($this->db_error('Connection error'));
						
					}
					
					
					if ($connect){
					
						if ($CONFIG[$this->keyconfig]['app_status'] == 'Production'){
							@mssql_select_db(trim($dbConfig[$dbuse]['name']), $connect) or die ($this->db_error('No Database Selected'));	
						
						}else{
							mssql_select_db(trim($dbConfig[$dbuse]['name']),$connect) or die ($this->db_error('No Database Selected'));
							
						}
						
						$this->link = $connect;
						return $connect;
					
					}else{
					
						return false;
					}
				}
				break;

				case 'sqlsrv':
				{
					$connectionInfo = array( "Database"=>$dbConfig[$dbuse]['name'], "UID"=>$dbConfig[$dbuse]['user'], "PWD"=>$dbConfig[$dbuse]['pass']);
					if ($CONFIG[$this->keyconfig]['app_status'] == 'Production'){
						$connect = @sqlsrv_connect(trim($dbConfig[$dbuse]['host']), $connectionInfo);
					
					}else{
						$connect = @sqlsrv_connect(trim($dbConfig[$dbuse]['host']), $connectionInfo);
						
					}
					
					
					if ($connect){
						
						$this->link = $connect;
						return $connect;
					
					}else{
						echo "Connection could not be established.<br />";
     					die( print_r( sqlsrv_errors(), true));
						return false;
					}
				}
				break;
				
				default :
				{
					$this->db_error('Database not configure, please check database server configure');
				}
				break;
			}
		}
	}
	
        
	/*
		fungsi query digunakan untuk menjalankan query seperti insert, 
		update atau query yang tidak diperlukan nilai kembalian dalam bentuk data
	 */
	public function query($data, $dbuse=0)
	{
		global $dbConfig, $CONFIG;
		$this->keyconfig = $this->setAppKey();
		
		logFile($data);
		$connection = $this->open_connection($dbuse);
                // cek server database yang dipakai
		switch ($dbConfig[$dbuse]['server'])
		{
			case 'mysql':
				if ($CONFIG[$this->keyconfig]['app_status'] == 'Production'){
						// if ($this->dbConfig[''])
						$this->var_query = @mysql_query($data);

				}else{
						$this->var_query = mysql_query($data) or die ($this->error($data,$dbuse));
				}
				break;

			case 'mssql':
				if ($CONFIG[$this->keyconfig]['app_status'] == 'Production'){
						// if ($this->dbConfig[''])
						$this->var_query = @mssql_query($data);

				}else{
						$this->var_query = mssql_query($data) or die ($this->error($data,$dbuse));
				}
				break;

			case 'sqlsrv':
				if ($CONFIG[$this->keyconfig]['app_status'] == 'Production'){
						// if ($this->dbConfig[''])
						$this->var_query = @sqlsrv_query($connection,$data);

				}else{
						$this->var_query = sqlsrv_query($connection,$data) or die ($this->error($data,$dbuse));
				}
				break;	
			
		}
		// $this->close_connection();
		
		return $this->var_query;
	}
	

	
	public function fetch($data=false, $loop=false, $dbuse=0)
	{
		/* $dbuse [0] = config default database */
		// pr($dbuse);
		global $dbConfig, $CONFIG;
		
		if (!$data) return false;
		
		$dataArray = array();
		$this->keyconfig = $this->setAppKey();
		
		switch ($dbConfig[$dbuse]['server'])
		{
			case 'mysql':
				$this->open_connection($dbuse);
				$this->var_result = $this->query($data,$dbuse) or die ($this->error($data,$dbuse));
				if ($loop){
					if ($this->num_rows($data,$dbuse)){

						while ($data = mysql_fetch_assoc($this->var_result)){
								$dataArray[] = $data;
						}
						
						return $dataArray;
					}else{
						return false;
					}
				}else{
					
					$dataArray = mysql_fetch_assoc($this->var_result);
					
					return $dataArray;
				}
				$this->close_connection();
			break;

			case 'mssql':
				$this->open_connection($dbuse);
				$this->var_result = $this->query($data,$dbuse) or die ($this->error($data,$dbuse));
				if ($loop){
					if ($this->num_rows($data,$dbuse)){

						while ($data = mssql_fetch_assoc($this->var_result)){
								$dataArray[] = $data;
						}
						
						return $dataArray;
					}else{
						return false;
					}
				}else{
					
					$dataArray = mssql_fetch_assoc($this->var_result);
					
					return $dataArray;
				}
				$this->close_connection();
			break;

			case 'sqlsrv':
				$connection = $this->open_connection($dbuse);

				$this->var_query = sqlsrv_query($connection,$data) or die ($this->error($data,$dbuse));
				if ($loop){
					if ($this->num_rows($data,$dbuse)){

						while ($row = sqlsrv_fetch_array($this->var_query, SQLSRV_FETCH_ASSOC)){
								$dataArray[] = $row;
						}
						return $dataArray;
					}else{
						return false;
					}
				}else{
					
					$dataArray = sqlsrv_fetch_array($this->var_query, SQLSRV_FETCH_ASSOC);
					
					return $dataArray;
				}
				$this->close_connection();
			break;
                    
		}
		
		
		
	}

	public function insert($data=false, $table=false, $dbuse=0)
	{
		/* $dbuse [0] = config default database */
		// pr($dbuse);
		global $dbConfig, $CONFIG;
		
		if (!$data) return false;
		
		$dataArray = array();
		$this->keyconfig = $this->setAppKey();
		
		$this->open_connection($dbuse);
		
		foreach ($data as $key => $val) {
            $tmpfield[] = $key;
            $tmpvalue[] = "'$val'";
        }

        $field = implode(',', $tmpfield);
        $value = implode(',', $tmpvalue);

        $query = "INSERT INTO {$table} ({$field}) VALUES ($value)";

        $result = $this->query($query,$dbuse);

        return true;

		$this->close_connection();
		
	}
        
        /* fungsi yang digunakan untuk execute query pada oracle secara otomatis akan di commit
         * jika fungsi ini dijalankan maka data yang di input tidak akan bisa di rollback kembali
         * ini sudah ketentuan dari API oracle-nya 
         */
	
	
	public function fetch_field($data)
	{
		$this->var_result = $data;
		
		return mysql_fetch_field($this->var_result);
	}
	
	public function num_rows($data=false,$dbuse)
	{
		global $dbConfig;

		if (!$data) return false;
		$result = $this->query($data,$dbuse) or die ($this->error($data));

		switch ($dbConfig[0]['server'])
		{
			case 'mysql':
				$numRec = mysql_num_rows($result);
				break;
			case 'mssql':
				$numRec = mssql_num_rows($result);
				break;
			case 'sqlsrv':
				$numRec = sqlsrv_num_rows($result);
				break;
		   
		}

		return $result;
	}
	
	public function insert_id()
	{
		$res['lastID'] = 0;
		$sql = "SELECT LAST_INSERT_ID() AS lastID";
		$res = $this->fetch($sql);
		
		if ($res['lastID']>0)return $res['lastID'];
		return false;
	}
	
	public function close_connection()
	{
		
		global $dbConfig;
		$this->keyconfig = $this->setAppKey();
		
		switch ($dbConfig[0]['server'])
		{
			case 'mysql':
				return mysql_close($this->link);
				break;
			case 'mssql':
				return mssql_close($this->link);
				break;
			case 'sqlsrv':
				return sqlsrv_close($this->link);
				break;
		   
		}
		
	}
	
	public function free_result($result)
	{
		return mysql_free_result($result);
	}
	
	public function real_escape_string($data)
	{
		return mysql_real_escape_string($data);
	}
	
	public function error($data,$dbuse)
	{
		
		global $dbConfig, $CONFIG;
		$this->keyconfig = $this->setAppKey();
		
		if ($CONFIG[$this->keyconfig]['app_status'] == 'Production'){
			$message = 'Your query error, please check again';
			return $message;
		}else{
			
			switch ($dbConfig[$dbuse]['server'])
			{
				case 'mysql':
					return mysql_error($this->link);
					break;

				case 'mssql':
					return mssql_get_last_message();
					break;	

				case 'sqlsrv':
					if( $data === false ) {
					    if( ($errors = sqlsrv_errors() ) != null) {
					        foreach( $errors as $error ) {
					            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					            echo "code: ".$error[ 'code']."<br />";
					            echo "message: ".$error[ 'message']."<br />";
					        }
					    }
					}
					break;	
				
			}
			
		}
		
	}
	
	function db_error($mesg)
	{
		
		if ($mesg !='') {
			$mesg = $mesg;
			
		}else{
			$mesg = "Message error not defined";
		}
		
		print ($mesg);
		return false;
		
	}
	
	function autocommit($val=0,$dbuse=0)
	{
		$command = "SET autocommit={$val};";
		$result = $this->query($command) or die ($this->error('autocommit failed'));
		// if (!$this->link){
			// $this->link = $this->open_connection(0);
		// }
		
		// return mysql_autocommit($this->link, false);
		
	}
	
	function commit($dbuse=0)
	{
		$command = "COMMIT;";
		$result = $this->query($command) or die ($this->error('commit failed'));
		// mysql_commit();
	}
	
	function rollback($dbuse=0)
	{
		$command = "ROLLBACK;";
		$result = $this->query($command) or die ($this->error('rollback failed'));
		// if (!$this->link){
			// $this->link = $this->open_connection(0);
		// }
		
		// pr($this->link);
		// mysql_rollback($this->link);
		
	}
	
	function begin($dbuse=0)
	{
		
		$this->autocommit();
		$command = "START TRANSACTION;";
		$result = $this->query($command) or die ($this->error('commit failed'));
		// if (!$this->link){
			// $this->link = $this->open_connection(0);
			
		// }
		// mysql_
		// $res = mysql_begin_transaction($this->link);
		if ($result) return true;
		return false;
	}

	function lazyQuery($data=array(), $debug=false, $method=0)
	{


		/*
		How to use lazyQuery !!!

		$sql1 = array(
                'table'=>'satker AS s, aset AS a, log_aset l',
                'field'=>'s.Satker_ID, s.KodeSektor, a.Aset_ID, l.last_aset_id',
                'condition'=>'s.Satker_ID IN(1,2)',
                'limit' => 5,
                'joinmethod' => 'LEFT JOIN',
                'join' => 's.Satker_ID = a.Aset_ID, a.Aset_ID = l.Aset_ID' 
                );
		*/

		$table = $data['table'];
		$field = $data['field'];

		switch ($method) {
			case '0':
				
				$condition = $data['condition'];
				$limit = intval(@$data['limit']);
				if ($limit>0) $limit = " LIMIT {$limit}";
				else $limit = "";
				$where = "";
				if ($condition) $whereCondition = " {$condition} ";
				else $whereCondition = " 1 ";

				if (isset($data['join'])){

					$jointmp = $data['join'];
					$join = explode(',', $jointmp);

					if (isset($data['joinmethod'])) $joinmethod = $data['joinmethod'];
					if ($joinmethod){
						$tmpTable = explode(',', $table);
						$length = count($tmpTable);

						$joinIndex = 0;
						for ($i=1; $i<$length; $i++){
							$tatement[] = $joinmethod . $tmpTable[$i] . ' ON ' . $join[$joinIndex];
							$joinIndex++;
						}

						$tmpStatement = implode(' ',$tatement);

						$primaryTable = $tmpTable[0];

						$sql = "SELECT {$field} FROM {$primaryTable} {$tmpStatement} WHERE {$whereCondition} {$limit}";
					}

				}else{
					$sql = "SELECT {$field} FROM {$table} WHERE {$whereCondition} {$limit}";
				} 
				
				
				logFile($sql);
				
				if ($debug){
					if ($debug>1){
						pr($sql);
					}else{
						pr($sql);
						exit;	
					} 
					
				}
				if (!$debug) $res = $this->fetch($sql,1);
				if ($res) return $res;

			break;
			
			case '1':
				/*
				$sql = array(
                'table'=>'aset',
                'field'=>'Aset_ID, KodeSetkor',
                'value'=>'111,1010',
                );
				*/
				$value = $data['value'];

				$sql = "INSERT INTO {$table} ({$field}) VALUES ({$value})";
				logFile($sql);
				if ($debug){
					if ($debug>1){
						pr($sql);
					}else{
						pr($sql);
						exit;	
					} 
					
				}
				if (!$debug) $res = $this->query($sql);
				if ($res) return true;

			break;

			case '2':
				/*		

				$sql = array(
                'table'=>'aset',
                'field'=>'Aset_ID = 1, KodeSatker = 1010',
                'condition'=>'Status_Validasi_Barang = 1',
                );
				*/
				$condition = $data['condition'];

				if (isset($data['limit'])) $limit = intval($data['limit']);
				else $limit = " ";
				
				if ($limit>0) $limit = " LIMIT {$limit}";
				else $limit = "";

				$sql = "UPDATE {$table} SET {$field} WHERE {$condition} {$limit}";
				if ($debug){
					if ($debug>1){
						pr($sql);
					}else{
						pr($sql);
						exit;	
					} 
					
				}
				if (!$debug)$res = $this->query($sql);
				if ($res) return true;

			break;


			default:
				pr('Method no defined');
				exit;
				break;
		}

		
		return false;
	}

	function getTableList()
	{
		global $dbConfig;

		
		if ($dbConfig[0]['server']=='mysql'){
			$sql = "SHOW TABLES FROM {$dbConfig[0]['name']}";
			$res = $this->fetch($sql,1);
			if ($res){
				foreach ($res as $key => $value) {
					$listTable[] = $value['Tables_in_' . $dbConfig[0]['name']];
				}
				return $listTable;	
			} 
		}
		
		if ($dbConfig[0]['server']=='mssql' OR $dbConfig[0]['server']=='sqlsrv'){
			$sql = "SELECT TABLE_NAME FROM {$dbConfig[0]['name']}.information_schema.tables";
			$res = $this->fetch($sql,1);
			if ($res){
				foreach ($res as $key => $value) {
					$listTable[] = $value['TABLE_NAME'];
				}
				return $listTable;	
			} 
		}
		return false;
	}

	function getTableStructure($table)
	{
		global $dbConfig;

		if ($dbConfig[0]['server']=='mysql'){
			$sql = "DESC {$table}";
		}

		if ($dbConfig[0]['server']=='mssql' OR $dbConfig[0]['server']=='sqlsrv'){
			$sql = "SELECT * FROM {$dbConfig[0]['name']}.INFORMATION_SCHEMA.COLUMNS WHERE table_name = '{$table}'";
		
		}

		$res = $this->fetch($sql,1);
		if ($res){
			foreach ($res as $key => $value) {
				$data['Field'][] = $value['COLUMN_NAME'];
			}
	
			return $data;	
		} 	
		return false;
	}

	function cacheTable()
	{
		$pathcache = CACHE . 'table/';

		if (!file_exists($pathcache)) {
			mkdir($pathcache);
		}

		$getTableList = $this->getTableList();
		
		if ($getTableList){

			foreach ($getTableList as $key => $value) {
				if (!file_exists($pathcache . $value)){

					$sturcture = $this->getTableStructure($value);

					logFile('create table cache '. $value);
					$handle = fopen($pathcache.$value, "a");
					
					fwrite($handle, serialize($sturcture) ."\n");
					fclose($handle);
					
				}
			}
		}
		
	}

	function save($method = "insert", $table=false, $data=false, $condition=false, $debug=false)
	{

		// $method = $param['method'];
		// $action = $param['action'];
		global $dbConfig;

		$mysql = 1;
		if ($dbConfig[0]['server']=='mssql' OR $dbConfig[0]['server']=='sqlsrv'){
			$mysql = 0;
		}

		$filepath = CACHE . 'table/';
		if ($table){
			if (is_array($table)){

			}else{
				$openFile = openFile($filepath . $table);

				$tablestructure = $this->unserialTable($openFile);
				if ($data){
					foreach ($data as $key => $value) {
						if (in_array($key, $tablestructure['fields'])){
							
							if ($mysql) $fields[] = "`{$key}`";
							else $fields[] = "{$key}";
							$values[] = "'{$value}'";
							
							if ($mysql) $field_values[] = "`{$key}` = '{$value}'";
							else $field_values[] = "{$key} = '{$value}'";
						}
					}

					$impFields = implode(',', $fields);
					$impValues = implode(',', $values);
					$impfield_values = implode(',', $field_values);

					if ($method == 'insert') {
						$sql = array(
				                    'table' =>"{$table}",
				                    'field' => "{$impFields}",
				                    'value' => "{$impValues}",
				                );
						$runmethod = 1;
					} else if ($method == 'update') {
						$sql = array(
				                    'table' =>"{$table}",
				                    'field' => "{$impfield_values}",
				                    'condition' => "{$condition}",
				                );
						$runmethod = 2;
					}
					
			        $result = $this->lazyQuery($sql,$debug,$runmethod);
			        if ($result) return true;
			        return false;
				}
			}
		}
	}

	function unserialTable($serialize=false, $rawdata=false)
	{

		global $dbConfig;

		if ($dbConfig[0]['server']=='mysql'){

			$unserial = unserialize($serialize);
			$dataArr = array();
			if ($unserial){
				foreach ($unserial as $key => $value) {
					$dataArr['fields'][] = $value['Field'];
				}

				if ($rawdata) $dataArr['raw'] = $unserial;
				return $dataArr;
			}
		}

		if ($dbConfig[0]['server']=='mssql' OR $dbConfig[0]['server']=='sqlsrv'){
			$unserial = unserialize($serialize);
			$dataArr = array();
			
			$dataArr['fields'] = $unserial['Field'];	
			return $dataArr;
			
		}

		
		return false;
	}

	function fetchSingleTable($table=false, $condition=array(), $order=false, $additional=array(), $debug=false)
	{

		global $dbConfig;

		$imp = 1;
		if ($order) $filter = "ORDER BY {$order}";

		$dataIn = array();
		if ($additional){
			$dataIn = $additional['in'];
		}
		if ($condition){
			foreach ($condition as $key => $value) {
				if ($value){
					if ($dbConfig[0]['server']=='mysql'){
						$field[] = "`{$key}` = '{$value}'";
					}else{

						if (count($dataIn)>0){
							if (in_array($key, $dataIn)){
								$field[] = "{$key} IN ({$value})";
							}else{
								$field[] = "{$key} = '{$value}'";
							}	
						}else{
							$field[] = "{$key} = '{$value}'";
						}
						
						
					}
					
				}
				
			}
			$imp = implode(' AND ', $field);
		}

		$sql = array(
                'table'=>"{$table}",
                'field'=>"*",
                'condition' => "{$imp} {$filter}"
                );

        $res = $this->lazyQuery($sql,$debug);
        if ($res) return $res;
        return false;
	}
}


?>