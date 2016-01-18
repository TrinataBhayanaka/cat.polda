<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

class home extends Controller {
	
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
		
		$this->contentHelper = $this->loadModel('contentHelper');
		// $this->marticle = $this->loadModel('marticle');
		// $this->mquiz = $this->loadModel('mquiz');
		// $this->mcourse = $this->loadModel('mcourse');
		$this->mhome = $this->loadModel('mhome');
	}
	
	public function index(){
	
		
		return $this->loadView('home/home');

	}
	public function StatusInfo(){
		$kotak_masuk= $this->mhome->select_data_km();
		// pr($kotak_masuk);
		$sudah_terbaca= $this->mhome->select_data_st();
		// pr($sudah_terbaca);
		$belum_terbaca= $this->mhome->select_data_bt();
		// pr($belum_terbaca);
		
		$newformat = array('km'=>$kotak_masuk,'st'=>$sudah_terbaca,'bt'=>$belum_terbaca);
		print json_encode($newformat);
		// print json_encode($register_user);
		exit;
	}
	
	public function chart(){
		$years = date('Y');
		$month = date('m');
		$date  = date('d');
		$month_rev = $years.'-'.$month.'-'.$date;
		setlocale (LC_ALL, 'IND');
		$newformatdate= strftime( "%B", strtotime($month_rev));
		
		// $newformatdate= $month;
		
		/*$aktif= $this->mhome->select_data_a($years,$month);
		// pr($kotak_masuk);
		$ditinjak_lanjuti= $this->mhome->select_data_dl($years,$month);
		// pr($sudah_terbaca);
		$tidak_ditinjak_lanjuti= $this->mhome->select_data_tdl($years,$month);
		// pr($belum_terbaca);
		$non_aktif= $this->mhome->select_data_na($years,$month);
		$newformat = array('a'=>$aktif,'dl'=>$ditinjak_lanjuti,'tdl'=>$tidak_ditinjak_lanjuti,'na'=>$non_aktif,'month'=>$newformatdate,'years'=>$years);*/
		
		$proses 	= $this->mhome->select_data_proses($years,$month);
		$selesai 	= $this->mhome->select_data_selesai($years,$month);
		$newformat = array('proses'=>$proses,'selesai'=>$selesai,'month'=>$newformatdate,'years'=>$years);
		
		
		print json_encode($newformat);
		// print json_encode($register_user);
		exit;
	}
	
	public function chart_condtn(){
		
		$month = $_POST['monthid'];
		$years = $_POST['yearid'];
		$date  = date('d');
		
		$month_rev = $years.'-'.$month.'-'.$date;
		setlocale (LC_ALL, 'IND');
		$newformatdate= strftime( "%B", strtotime($month_rev));
		
		/*$aktif= $this->mhome->select_data_a($years,$month);
		// pr($kotak_masuk);
		$ditinjak_lanjuti= $this->mhome->select_data_dl($years,$month);
		// pr($sudah_terbaca);
		$tidak_ditinjak_lanjuti= $this->mhome->select_data_tdl($years,$month);
		// pr($belum_terbaca);
		$non_aktif= $this->mhome->select_data_na($years,$month);*/
		
		// $newformat = array('a'=>$aktif,'dl'=>$ditinjak_lanjuti,'tdl'=>$tidak_ditinjak_lanjuti,'na'=>$non_aktif,'month'=>$newformatdate,'years'=>$years);
		// $newformat = array('a'=>$aktif,'dl'=>$ditinjak_lanjuti,'tdl'=>$tidak_ditinjak_lanjuti,'na'=>$non_aktif);
		
		$proses 	= $this->mhome->select_data_proses($years,$month);
		$selesai 	= $this->mhome->select_data_selesai($years,$month);
		$newformat = array('proses'=>$proses,'selesai'=>$selesai,'month'=>$newformatdate,'years'=>$years);
		print json_encode($newformat);
		// print json_encode($register_user);
		exit;
	}

	public function chart_bar_default(){
		
		// $month = $_POST['monthid'];
		$month ='';
		
		// $years = $_POST['yearid'];
		$years = '';
		
		$date  = date('d');
		
		$month_rev = $years.'-'.$month.'-'.$date;
		setlocale (LC_ALL, 'IND');
		// $newformatdate= strftime( "%B", strtotime($month_rev));
		$newformatdate= '';
		
		$aktif= $this->mhome->select_data_a_default();
		// pr($kotak_masuk);
		$ditinjak_lanjuti= $this->mhome->select_data_dl_default();
		// pr($sudah_terbaca);
		$tidak_ditinjak_lanjuti= $this->mhome->select_data_tdl_default();
		// pr($belum_terbaca);
		$non_aktif= $this->mhome->select_data_na_default();
		
		$newformat = array('a'=>$aktif,'dl'=>$ditinjak_lanjuti,'tdl'=>$tidak_ditinjak_lanjuti,'na'=>$non_aktif,'month'=>$newformatdate,'years'=>$years);
		// $newformat = array('a'=>$aktif,'dl'=>$ditinjak_lanjuti,'tdl'=>$tidak_ditinjak_lanjuti,'na'=>$non_aktif);
		print json_encode($newformat);
		// print json_encode($register_user);
		exit;
	}

	public function viewvisitor(){
		
		//register user
		$register_user= $this->mhome->select_data_register_user();
		$this->view->assign('register_user',$register_user);
		// pr($register_user);
		//visitor
		$visitor_user= $this->mhome->select_data_visitor_user();
		$this->view->assign('visitor_user',$visitor_user);
		
		return $this->loadView('home/visitor');
	}
	
	public function chart_donut_visitor(){
		
		$register_user= $this->mhome->select_data_register_user();
		$visitor_user= $this->mhome->select_data_visitor_user();
		
		$newformat = array('register'=>$register_user,'visitor'=>$visitor_user);
		print json_encode($newformat);
		// print json_encode($register_user);
		exit;
	}
	
	public function chart_donut_visitor_condtn(){
		$monthid = $_POST['monthid'];
		$yearid = $_POST['yearid'];
		
		$register_user= $this->mhome->select_data_register_user_condt($monthid,$yearid);
		// pr($register_user);
		$visitor_user= $this->mhome->select_data_visitor_user_condt($monthid,$yearid);
		// pr($visitor_user);
		$newformat = array('register'=>$register_user,'visitor'=>$visitor_user);
		print json_encode($newformat);
		// print json_encode($register_user);
		exit;
	}
	
	function cetak()
    {
		global $basedomain;
		// pr($_POST);
		// exit;
			//register user
			$monthid = $_POST['month'];
			$yearid = $_POST['year'];
			
			// $statistic_month = $this->mcourse->getTest($month,$year);
			// $statistic = $statistic_month['total'];
			
			$register_user= $this->mhome->select_data_register_user_condt($monthid,$yearid);
			$user_register = $register_user['total'];
			
			//visitor
			$visitor_user= $this->mhome->select_data_visitor_user_condt($monthid,$yearid);
			$user_visitor = $visitor_user['total'];
			
		if($_POST['type'] == 1){
			// exit;
			$this->reportHelper =$this->loadModel('reportHelper');
			$html = "<div style=\"width: ; text-align: center;\">
						<h4>List User : </h4>
						 <table style=\"text-align: center; border-collapse: collapse; margin-left: auto; margin-right: auto; width: 100%;\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
							<thead>
								<tr class=\"\" >
									<td>registered users</td>
									<td>users who just browse the web <br>without login</td>
								</tr>
								</thead>
								<tbody>
									<tr>
										<td align=\"center\">$user_register</td>
										<td align=\"center\">$user_visitor</td>	
									</tr>
								</tbody>
							</table>
						</div>";

			$generate = $this->reportHelper->loadMpdf($html, 'visitor');
		}else{
			$waktu=date("d-m-y_h:i:s");
			$filename ="visitor_$waktu.xls";
			header('Content-type: application/ms-excel');
			header('Content-Disposition: attachment; filename='.$filename);
			$html = "<div style=\"width: ; text-align: center;\">
						<h4>List User : </h4>
						 <table style=\"text-align: center; border-collapse: collapse; margin-left: auto; margin-right: auto; width: 100%;\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
							<thead>
								<tr class=\"center\" >
									<td align=\"center\">registered users</td>
									<td align=\"center\">users who just browse the web <br>without login</td>
								</tr>
								</thead>
								<tbody>
									<tr>
										<td align=\"center\">$user_register</td>
										<td align=\"center\">$user_visitor</td>	
									</tr>
								</tbody>
							</table>
						</div>";
			echo $html;
			exit;
		}
	}
	
	public function statistik(){
		

		return $this->loadView('home/statistik');

	}
	
	public function search(){
		// pr($_POST);
		if($_POST){

        $data['pencarian']=$this->contentHelper->tracking($_POST['tracking'],$_POST['type'],$_POST['start'],$_POST['end']);
        
        // pr($data);
    	}
        $this->view->assign('data',$data['pencarian']);
        // pr($data);
        // exit;
		return $this->loadView('home/search');

	}

	public function selectData(){
	
		$type=$_POST['type'];
		if($type=="2"){
			$dataContent['table'] = "bsn_kategori";
			$dataContent['condition'] = array('n_status'=>1);

			$data = $this->contentHelper->fetchData($dataContent);
		
			$this->view->assign('data',$data);
		}elseif($type=="3"){
			$dataContent['table'] = "bsn_satker";
			$dataContent['condition'] = array('n_status'=>1);

			$data = $this->contentHelper->fetchData($dataContent);
		
			$this->view->assign('data',$data);
		}

		$this->view->assign('type',$type);
        $dataView= $this->loadView('home/selectData');
// pr($dataView);
        if ($dataView){
            print json_encode(array('status'=>true,'data'=>$dataView));
        }else{
            print json_encode(array('status'=>false));
        }
        
        exit;

	}

	
}

?>
