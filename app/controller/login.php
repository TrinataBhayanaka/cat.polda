<?php

class login extends Controller {
	
	var $models = FALSE;
	var $view;
    var $loadSession = false;
	
	function __construct()
	{
		global $basedomain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$this->view->assign('basedomain',$basedomain);
        //$this->loadSession = new Session;

    }
	
	function loadmodule()
	{
        $this->contentHelper = $this->loadModel('contentHelper');
        $this->loginHelper = $this->loadModel('loginHelper');
        $this->userHelper = $this->loadModel('userHelper');
        $this->models = $this->loadModel('mlogin');
	}
	
	function index(){
        
        $materi = $this->models->getData('ujian',1,'status = 1');
        $lokasi = $this->models->getData('lokasi',1,'status_pemanfaatan = 1');

        foreach ($materi as $key => $value) {
            $detail = $this->models->getData('master_kategori',0,"id_master = {$value['id_kategori']}");
            $materi[$key]['detail'] = $detail;
        }
        
        $this->view->assign('materi',$materi);
        $this->view->assign('lokasi',$lokasi);

    	return $this->loadView('login');
    }
    

    function local()
    {
        // db($_POST);
        global $basedomain, $CONFIG;
        if (isset($_POST['token'])){

            $validateData = $this->loginHelper->local($_POST);
           // db($validateData);
            if ($validateData){
                // db($basedomain.$CONFIG['default']['default_view']);
                redirect($basedomain.$CONFIG['default']['default_view']);
            }else{
                redirect($basedomain.$CONFIG['default']['login']);
            }

        }

        
    }

    function ajaxCheckUser()
    {
        $idUser = $_POST['iduser'];

        $data = $this->models->getData('master_peserta',1,"no_peserta = {$idUser}");

        print json_encode($data);

        exit;
    }

    function ajaxgetRuang()
    {
        $idLokasi = $_POST['idLokasi'];

        $data = $this->models->getData('ruangan',1,"id_ruangan = {$idLokasi}");

        print json_encode($data);

        exit;
    }

    function generatePaket()
    {
        $ujian = $this->models->getData('ujian',0,"status=1");
        $soalstack = $this->models->getId($ujian['id_kategori']);
        
        $number = randomize_number($soalstack,$ujian['jumlah_soal'],$ujian['pilihan_paket']);
        
        $this->models->delete_data($ujian['id_kategori'],'paket_soal');
        
        $letters = range('A', 'Z');
        foreach ($number as $key => $val) {
            $data['id_kategori'] = $ujian['id_kategori'];
            $data['id_soal'] = implode(",", $val);
            $data['paket'] = $letters[$key];
            $data['waktu_acak'] = date('Y/m/d h:i:s', time());
            
            $this->models->insert_data($data,'paket_soal');
        }

        db('======= Generate Paket Selesai ========');
    }

    function generateSoal()
    {
        $paket = $this->models->getData('paket_soal',1,"id_kategori = 4");
        $getSoal = $this->models->getData('master_soal',1,"id_soal IN ({$paket[0]['id_soal']})");
       
        $total = 500;
        
        for($i=1;$i<=$total;$i++){

            $soal['id_paket'] = $paket[0]['id_paket'];
            $soal['id_kategori'] = $paket[0]['id_kategori'];
            $soal['paket'] = $paket[0]['paket'];

            $exp = explode(",", $paket[0]['id_soal']);
            $soal['soal'] = implode(",",shuffle_assoc($exp));

            $option = range(1, 4);
            $soal['opt'] = implode(",",shuffle_assoc($option));
            
            $this->models->insert_data($soal,'generated_soal');

        }


         db('======= Generate Soal Selesai ========');
    }
    

    /* DHITA DEMO */
    function demo_1(){
        return $this->loadView('demo/mulai-1');
    }
    function demo_2(){
        return $this->loadView('demo/mulai-2');
    }
    function demo_3(){
        return $this->loadView('demo/mulai-3');
    }
    function demo_4(){
        return $this->loadView('demo/mulai-4');
    }
    function demo_5(){
        return $this->loadView('demo/mulai-5');
    }
    function demo_6(){
        return $this->loadView('demo/mulai-6');
    }

    

}

?>
