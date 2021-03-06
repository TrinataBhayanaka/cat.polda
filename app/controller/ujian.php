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
		global $basedomain, $CONFIG;

        $ujian = $this->models->getData('ujian',0,"status = 1");
        if($ujian['id_ujian']){
            $detailujian = $this->models->getData('master_kategori',0,"id_master = {$ujian['id_kategori']}");
            $paket = $this->models->getData('paket_soal',0,"id_kategori = {$ujian['id_kategori']} AND status = 1");
            $tmp_soal = $this->models->getData('generated_soal',0,"id_paket = {$paket['id_paket']} AND id_peserta = {$this->user['id_peserta']} AND id_ujian = {$ujian['id_ujian']}");

            setcookie('idgen',$tmp_soal['id'],time() + 10800);
            if($tmp_soal['status'] == 3){
                redirect($basedomain."ujian/result");
                exit;
            } elseif ($tmp_soal['status'] == 4) {
                session_destroy();
                
                redirect($basedomain);
            }

            $getSoal = $this->models->getData('master_soal',1,"id_soal IN ({$tmp_soal['soal']})");
            foreach ($getSoal as $key => $value) {
                $getSoal[$key]['soal'] = html_entity_decode(htmlspecialchars_decode($value['soal'],ENT_NOQUOTES));
                $getSoal[$key]['1'] = html_entity_decode(htmlspecialchars_decode($value['1'],ENT_NOQUOTES));
                $getSoal[$key]['2'] = html_entity_decode(htmlspecialchars_decode($value['2'],ENT_NOQUOTES));
                $getSoal[$key]['3'] = html_entity_decode(htmlspecialchars_decode($value['3'],ENT_NOQUOTES));
                $getSoal[$key]['4'] = html_entity_decode(htmlspecialchars_decode($value['4'],ENT_NOQUOTES));
            }
            $opts = unserialize($tmp_soal['opt']);
            
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
                $opt = explode(",", $opts[$key]);
                foreach ($opt as $j => $vals) {
                   $soalSort[$key]['pilihan'][$j]['full'] = $letters[$j].". ".$value[$vals];
                   $soalSort[$key]['pilihan'][$j]['opt'] = $letters[$j];
                   $soalSort[$key]['pilihan'][$j]['ajax'] = $letters[$j]."|".$value[$vals];
                }
            }

            foreach ($soalSort as $key => $value) {
                $kisi = $this->models->getData('master_kategori',0,"id_master = {$value['kisi']}");
                $soalSort[$key]['kisi'] = $kisi['nama_master'];

                $jwb = $this->models->getData('jawaban',0,"id_kategori = {$value['id_kategori']} AND id_soal = {$value['id_soal']} AND id_peserta = {$this->user['id_peserta']} AND id_ujian = {$ujian['id_ujian']}");
                $soalSort[$key]['jawaban'] = $jwb['jawaban'];
                $soalSort[$key]['opt'] = $jwb['opt'];
                $soalSort[$key]['fulljwb'] = $jwb['opt'].". ".$jwb['jawaban'];

            }
            // db($soalSort);
            date_default_timezone_set("Asia/Bangkok");
            $info = explode(" ",date('D m d Y H:i:s O'));
            
            $hari = $info[0];
            $bulan = $info[1];
            $tanggal = $info[2];
            $tahun = $info[3];
            $waktu = explode(":",$info[4]);
            $jam = $waktu[0];
            $menit = $waktu[1];
            $detik = $waktu[2];

            $zona = $info[5];
            $this->view->assign('hari',$hari);
            $this->view->assign('bulan',$bulan);
            $this->view->assign('tanggal',$tanggal);
            $this->view->assign('tahun',$tahun);
            $this->view->assign('jam',$jam);
            $this->view->assign('menit',$menit);
            $this->view->assign('detik',$detik);
            $this->view->assign('zona',$zona);

            $this->view->assign('genSoal',$tmp_soal);
            $this->view->assign('status',$tmp_soal['status']);
            $this->view->assign('user',$this->user);
            $this->view->assign('ujian',$ujian);
            $this->view->assign('idgen', $tmp_soal['id']);
            $this->view->assign('detailujian',$detailujian);
            $this->view->assign('paket',$paket);
            $this->view->assign('soal',$soalSort);
            $this->view->assign('websocket',$CONFIG['default']['websocket']);
            
            // pr($this->user);
            return $this->loadView('home');
        } else {
            session_destroy();
            setcookie('id_peserta',null,-1);
            setcookie('id_kategori',null,-1);
            setcookie('id_ujian',null,-1);
            redirect($basedomain);
        }
        
		
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
        global $basedomain;

        $id = $_COOKIE['idgen'];
        $gen = $this->models->getData('generated_soal',0,"id = {$id}");
        $kategori = $this->models->getData('master_kategori',0,"id_master = {$gen['id_kategori']}");
        // $this->models->update_data("status_ujian = 3",'ujian',"status = 1 AND id_kategori = {$gen['id_kategori']}");

        // $jwbUser = $this->models->getData('jawaban',1,"id_ujian = {$gen['id_ujian']} AND id_peserta = {$gen['id_peserta']}");
        // db($jwbUser);
        // $ujian = $this->models->getData('ujian',0,"id_ujian = {$gen['id_ujian']}");
        // $benar = 0;
        // foreach ($jwbUser as $key => $value) {
            // if($value['kunci'] == $value['jawaban']){
                // $benar++;
            // }
        // }
        // $nilai = $benar/$ujian['jumlah_soal']*100;
        $this->models->update_data("status = 3",'generated_soal',"id = {$id}");
        // db('bisa');

        $gen = $this->models->getData('generated_soal',0,"id = {$id}");
        $getSoal = $this->models->getData('master_soal',1,"id_soal IN ({$gen['soal']})");
        foreach ($getSoal as $key => $value) {
            $getSoal[$key]['soal'] = html_entity_decode(htmlspecialchars_decode($value['soal'],ENT_NOQUOTES));
            $getSoal[$key]['1'] = html_entity_decode(htmlspecialchars_decode($value['1'],ENT_NOQUOTES));
            $getSoal[$key]['2'] = html_entity_decode(htmlspecialchars_decode($value['2'],ENT_NOQUOTES));
            $getSoal[$key]['3'] = html_entity_decode(htmlspecialchars_decode($value['3'],ENT_NOQUOTES));
            $getSoal[$key]['4'] = html_entity_decode(htmlspecialchars_decode($value['4'],ENT_NOQUOTES));
        }
        $opts = unserialize($gen['opt']);
        
        $exp = explode(",", $gen['soal']);
        $opt = explode(",", $gen['opt']);
        foreach ($exp as $key => $value) {
            foreach ($getSoal as $k => $val) {
                if($value == $val['id_soal']){
                    $soalSort[$key] = $val;
                }
            }
        }
        
        $letters = range('A', 'Z');
        foreach ($soalSort as $key => $value) {
            $opt = explode(",", $opts[$key]);
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

        $now = strtoupper(changeDate($gen['waktu_mulai']));
        // db($soalSort);
        $this->view->assign('paket',$gen['paket']);
        $this->view->assign('soal',$soalSort);
        $this->view->assign('kategori',$kategori);
        $this->view->assign('user',$this->user);
        $this->view->assign('tanggal',$now);
        $this->view->assign('skor',$gen['nilai']);


        $this->reportHelper =$this->loadModel('reportHelper');

        $html =$this->loadView('kertaSoal');
        $ruangan = str_replace("/", "_", $this->user['id_ruangan']);
        $generate = $this->reportHelper->loadMpdf($html, $ruangan."-".$this->user['nama']."-".$kategori['nama_master'] ,LOGS);
        // sleep(10);
        redirect($basedomain."ujian/result");

    }

    function result()
    {
        global $basedomain;
        $id = $_COOKIE['idgen'];

        $newgen = $this->models->getData('generated_soal',0,"id = {$id}");
        if (!empty($newgen)) {
            $materi = $this->models->getData('master_kategori',0,"id_master = {$newgen['id_kategori']}");
            $user = $this->models->getData('master_peserta',0,"id_peserta = {$newgen['id_peserta']}");

            $waktu_ujian = date('Y-m-d H:i:s', time());
            $now = strtoupper(changeDate($waktu_ujian));

            $this->view->assign('nowdate',$now);
            $this->view->assign('soal',$newgen);
            $this->view->assign('materi',$materi);
            $this->view->assign('user',$user);

            return $this->loadView('hasil');
        } else {
            redirect($basedomain);
        }
    }


    function static_event()
    {
        date_default_timezone_set("Asia/Jakarta");
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        $id = $_POST['id'];
        sleep(1);
        $check = $this->models->getData('generated_soal',0,"id = {$id}");
        $check2 = $this->models->getData('ujian',0,"status = 1 AND id_kategori = {$check['id_kategori']}");
        $waktu_ujian = date('Y-m-d H:i:s', time());

        if($check2['status_ujian'] == 2 && ($check['waktu_mulai'] == '0000-00-00 00:00:00'|| $check['waktu_mulai'] == null)){
            // $this->models->update_data("waktu_ujian = '{$waktu_ujian}'",'ujian',"id_ujian = {$check2['id_ujian']}");
            $check2 = $this->models->getData('ujian',0,"status = 1 AND id_kategori = {$check['id_kategori']}");
            $this->models->update_data("waktu_mulai = '{$check2['waktu_ujian']}',status = 2",'generated_soal',"id = {$id}");
            $new = $this->models->getData('generated_soal',0,"id = {$id}");

            echo "{$new['waktu_mulai']}";
        } elseif ($check2['status_ujian'] == 2) {
            echo "{$check['waktu_mulai']}";
        } elseif ($check['status'] == 5){
            $this->models->update_data("waktu_mulai = '{$waktu_ujian}',status = 2",'generated_soal',"id = {$id}");
            $new = $this->models->getData('generated_soal',0,"id = {$id}");
            
            echo "{$new['waktu_mulai']}";
        } else {
            echo "1";
        }
        flush();
        exit;
    }

    function servertime()
    {
        date_default_timezone_set("Asia/Jakarta");
        $now = new DateTime(); 
        echo $now->format("M j, Y H:i:s O")."\n";
    }

    function ajaxaddJwb()
    {
        $id = $_COOKIE['idgen'];
        $data['id_soal'] = $_POST['idsoal'];
        $data['id_kategori'] = $_POST['idKategori'];
        $data['id_ujian'] = $_POST['idUjian'];
        $jwb = explode("|", $_POST['jwb']);
        $data['jawaban'] = htmlentities(htmlspecialchars($jwb[1], ENT_QUOTES));
        $data['opt'] = $jwb[0];
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

        // if($data['kunci'] == $data['jawaban']){
            $jwbUser = $this->models->getData('jawaban',1,"id_ujian = {$data['id_ujian']} AND id_peserta = {$data['id_peserta']}");
            // db($jwbUser);
            $ujian = $this->models->getData('ujian',0,"id_ujian = {$data['id_ujian']}");
            $benar = 0;
            foreach ($jwbUser as $key => $value) {
                if($value['kunci'] == $value['jawaban']){
                    $benar++;
                }
            }
            $nilai = $benar/$ujian['jumlah_soal']*100;
            $this->models->update_data("nilai = {$nilai}",'generated_soal',"id_ujian = {$data['id_ujian']} AND id_peserta = {$data['id_peserta']}");
        // }

        print json_encode(1);

        exit;
    }


}

?>
