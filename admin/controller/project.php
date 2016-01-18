<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class project extends Controller {
	
	var $models = FALSE;
	
	public function __construct()
	{
		parent::__construct();

		global $app_domain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$sessionAdmin = new Session;
		$this->admin = $sessionAdmin->get_session();
		// $this->validatePage();
		$this->view->assign('app_domain',$app_domain);
	}
	public function loadmodule()
	{
		$this->mproject = $this->loadModel('mproject');
	}
	
	public function index(){
		
		$data = $this->mproject->getData('hr_project');
		
		$this->view->assign('data',$data);

		return $this->loadView('project/index');

	}

	public function addproject()
	{
		$data = $this->mproject->getData('hr_project');
		
		$this->view->assign('data',$data);

		return $this->loadView('project/addProject');
	}

	public function ins_project()
	{
		global $basedomain;

		$_POST['description'] = htmlentities(htmlspecialchars($_POST['description'], ENT_QUOTES));
		$_POST['n_status'] = 1;
		$_POST['idRequired'] = implode(",", $_POST['idRequired']);

		if($_FILES['gambar']['error'] == 0)
		{
			$files = uploadFile('gambar');

			$_POST['image'] = $files['full_name'];
		}

		$this->mproject->insertData($_POST,'hr_project');

		echo "<script>alert('Data successfully inserted');window.location.href='".$basedomain."project'</script>";
		exit;
	}

	public function detail()
	{
		$data = $this->mproject->getSData('hr_project','idProject='.$_GET['id']);

		$data['description'] = html_entity_decode(htmlspecialchars_decode($data['description'], ENT_NOQUOTES));
		$data['date_start'] = changeDate($data['date_start']);
		$data['date_end'] = changeDate($data['date_end']);

		$this->view->assign('data',$data);

		return $this->loadView('project/detail');
	}

	public function people()
	{
		$data = $this->mproject->getSData('hr_project','idProject='.$_GET['id']);
		$req = $this->mproject->getDataCond('hr_project',"idProject IN ({$data['idRequired']})");
		$users = $this->mproject->getpeople($data['idRequired']);
		$team = $this->mproject->getTeam($_GET['id'],1);
		$participants = $this->mproject->getDataCond('hr_users',"type IN (2,3)");
		$listParticipants = $this->mproject->getTeam($_GET['id'],2);

		$data['date_start'] = changeDate($data['date_start']);
		$data['date_end'] = changeDate($data['date_end']);

		$this->view->assign('id',$_GET['id']);
		$this->view->assign('data',$data);
		$this->view->assign('users',$users);
		$this->view->assign('team',$team);
		$this->view->assign('participants',$participants);
		$this->view->assign('listParticipants',$listParticipants);
		$this->view->assign('required',$req);

		return $this->loadView('project/people');
	}

	public function addpeople()
	{
		$data = $this->mproject->getSData('hr_project','idProject='.$_GET['id']);

		$this->view->assign('id',$_GET['id']);
		$this->view->assign('data',$data);

		return $this->loadView('project/addPeople');
	}

	public function ins_team()
	{
		global $basedomain;

		$this->mproject->insertData($_POST,'hr_userproject');

		echo "<script>alert('Data successfully inserted');window.location.href='".$basedomain."project/people/?id={$_POST['idProject']}'</script>";
		exit;
	}

	public function del_people()
	{
		global $basedomain;

		$tmp = explode("a", $_GET['id']);

		$this->mproject->delete_people($tmp);

		echo "<script>alert('Data successfully deleted');window.location.href='".$basedomain."project/people/?id={$tmp[2]}'</script>";
		exit;
	}

	public function upd_project()
	{
		global $basedomain;

		$id = $_GET['id'];

		$this->mproject->updProject($id);

		echo "<script>alert('Thank you for your hard work. Project is completed');window.location.href='".$basedomain."project/detail/?id={$id}'</script>";
		exit;
	}

	
}

?>
