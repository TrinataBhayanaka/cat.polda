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
        $this->loadSession = new Session;

    }
	
	function loadmodule()
	{
        $this->contentHelper = $this->loadModel('contentHelper');
        $this->loginHelper = $this->loadModel('loginHelper');
        $this->userHelper = $this->loadModel('userHelper');
        $this->models = $this->loadModel('mlogin');
	}
	
	function index(){
        
        $materi = $this->models->getData('master_kategori',1);
        $lokasi = $this->models->getData('lokasi',1,'status_pemanfaatan = 1');

        $this->view->assign('materi',$materi);
        $this->view->assign('lokasi',$lokasi);

    	return $this->loadView('login');
    }
    

    function local()
    {
        // db($_POST);
        global $basedomain;
        if (isset($_POST['token'])){

            $validateData = $this->loginHelper->local($_POST);
           
            if ($validateData){
                // set session
                $setSession = $this->loadSession->set_session($validateData);
                    redirect($basedomain.'home');
                exit;
            }else{
                echo "<script>alert('Username atau Password anda salah');</script>";
                redirect($basedomain);exit;
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

        $data = $this->models->getData('ruangan',1,"id_lokasi = {$idLokasi}");

        print json_encode($data);

        exit;
    }
    
    

}

?>
