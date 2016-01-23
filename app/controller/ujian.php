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
        $tmp_soal = $this->models->getData('generated_soal',0,"id_paket = {$paket['id_paket']} AND id_peserta = {$this->user['id_peserta']}");
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
               $soalSort[$key]['pilihan'][$j]['full'] = $letters[$j].". ".$value[$vals];
               $soalSort[$key]['pilihan'][$j]['opt'] = $letters[$j];
            }
        }

        foreach ($soalSort as $key => $value) {
            $kisi = $this->models->getData('master_kategori',0,"id_master = {$value['kisi']}");
            $soalSort[$key]['kisi'] = $kisi['nama_master'];

            $jwb = $this->models->getData('jawaban',0,"id_kategori = {$value['id_kategori']} AND id_soal = {$value['id_soal']} AND id_peserta = {$this->user['id_peserta']}");
            $soalSort[$key]['jawaban'] = $jwb['jawaban'];
            $soalSort[$key]['opt'] = $jwb['opt'];
            $soalSort[$key]['fulljwb'] = $jwb['opt'].". ".$jwb['jawaban'];

        }
        // db($soalSort);
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

    function ajaxaddJwb()
    {
        $data['id_soal'] = $_POST['idsoal'];
        $data['id_kategori'] = $_POST['idKategori'];
        $jwb = explode(". ", $_POST['jwb']);
        if(count($jwb) == 2){
            $data['jawaban'] = $jwb[1];
            $data['opt'] = $jwb[0];
        } else {
            $data['jawaban'] = "";
            $data['opt'] = "";
        }

        $data['id_peserta'] = $this->user['id_peserta'];

        $soal = $this->models->getData('master_soal',0,"id_soal = {$data['id_soal']}");

        $data['kunci'] = $soal[1];

        $check = $this->models->getData('jawaban',0,"id_kategori = {$data['id_kategori']} AND id_soal = {$data['id_soal']} AND id_peserta = {$data['id_peserta']}");
        // pr($check);
        if(!$check){
            $this->models->insert_data($data,'jawaban');
        } else {
            $this->models->update_data("jawaban = '{$data['jawaban']}', opt = '{$data['opt']}'",'jawaban',"id_kategori = {$data['id_kategori']} AND id_soal = {$data['id_soal']} AND id_peserta = {$data['id_peserta']}");
        }

        

        print json_encode(1);

        exit;
    }

}

?>
