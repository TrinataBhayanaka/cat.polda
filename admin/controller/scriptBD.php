<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class scriptBD extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();

		global $app_domain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$sessionAdmin = new Session;
		$this->admin = $sessionAdmin->get_session();
		$this->view->assign('app_domain',$app_domain);
	}
	public function loadmodule()
	{
		
		$this->contentHelper = $this->loadModel('contentHelper');
		$this->model = $this->loadModel('mpengaduan');
	}
	
	public function index(){
		global $basedomain;

		redirect($basedomain);

	}
	
	public function truncateAllBuahahaha()
	{
		global $basedomain;
		
		$this->model->datatruncate();

		redirect($basedomain);
	}

	public function getAllUsersSipmas()
	{
		$data = $this->model->getAllUsers();
		db($data);
	}

	public function scriptFromTxt()
	{
		global $app_domain;
		$req = $_GET['wth'];
		$text = file_get_contents($app_domain."db/query.txt");
		
		$res = $this->model->exeQuery($text,$req);

		db($res);
	}
	
}

?>
