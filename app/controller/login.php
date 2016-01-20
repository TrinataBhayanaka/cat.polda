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
        
        $materi = $this->models->getData('master_kategori',1,'status = 1');
        $lokasi = $this->models->getData('lokasi',1,'status_pemanfaatan = 1');

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

    function generateSoal()
    {
        $number = UniqueRandomNumbersWithinRange(1,10,5);
        $paket = implode(",", $number);

        $data = $this->models->getData('bank_soal',1,"id_kategori = 4 AND kisi = 5 AND id_soal IN ({$paket})");
        $random = shuffle_assoc($data);
        db($random);
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
