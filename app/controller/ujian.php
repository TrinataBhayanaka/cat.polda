<?php

class ujian extends Controller {
	
	var $models = FALSE;
	var $view;
	var $reportHelper;
	
	function __construct()
	{
		global $basedomain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$this->view->assign('basedomain',$basedomain);
		$getUserLogin = $this->isUserOnline();
		$this->user = $getUserLogin[0];
        $this->token = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
    }
	
	function loadmodule()
	{
        $this->models = $this->loadModel('mlogin');
	}
	
	function index(){
		global $basedomain;

        $ujian = $this->models->getData('ujian',0,"id_kategori = 4 AND status = 1");
        $detailujian = $this->models->getData('master_kategori',0,"id_master = {$ujian['id_kategori']}");
        $paket = $this->models->getData('paket_soal',0,"id_kategori = 4 AND paket = 'A'");
        $tmp_soal = $this->models->getData('generated_soal',0,"id_paket = {$paket['id_paket']} ORDER BY RAND()");
        $getSoal = $this->models->getData('master_soal',1,"id_soal IN ({$tmp_soal['soal']})");
        $lokasi = $this->models->getData('lokasi',0,"id_lokasi = {$this->user['id_lokasi']}");
        $ruangan = $this->models->getData('ruangan',0,"id_ruangan = {$this->user['id_ruangan']}");
        
        $exp = explode(",", $tmp_soal['soal']);
        $opt = explode(",", $tmp_soal['opt']);
        foreach ($exp as $key => $value) {
            foreach ($getSoal as $k => $val) {
                if($value == $val['id_soal']){
                    $soalSort[$key] = $val;
                }
            }
        }

        $letters = range('A', 'Z');
        foreach ($soalSort as $key => $value) {
            foreach ($opt as $j => $vals) {
               $soalSort[$key]['pilihan'][] = $letters[$j].". ".$value[$vals];
            }
        }
        
        $this->view->assign('user',$this->user);
        $this->view->assign('lokasi',$lokasi);
        $this->view->assign('ruangan',$ruangan);
        $this->view->assign('ujian',$ujian);
        $this->view->assign('detailujian',$detailujian);
        $this->view->assign('paket',$paket);
        $this->view->assign('soal',$soalSort);

        return $this->loadView('home');
		
    }

    function logout()
    {
    	global $basedomain;

    	// $updateStatusNilai = $this->quizHelper->updateStatusNilai();
    	
    	$doLogout = $this->userHelper->logoutUser();
    	if ($doLogout){
    		redirect($basedomain.'logout.php');exit;
    	}else{
    		redirect($basedomain);
    		logFile('can not logout user');exit;
    	}
    }

    function hasil()
    {
    	return $this->loadView('hasil');
    }
    function static_event()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        $time = date('r');
        echo "data: bayu The server time is: {$time}\n\n";
        flush();
    }

}

?>
