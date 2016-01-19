<?php

class user extends Controller {
	
	var $models = FALSE;
	var $view;

	
	function __construct()
	{
		global $basedomain;
		$this->loadmodule();
		$this->view = $this->setSmarty();
		$this->view->assign('basedomain',$basedomain);
        $getUserLogin = $this->isUserOnline();
        $this->user = $getUserLogin[0];
    }
	
	function loadmodule()
	{
        $this->contentHelper = $this->loadModel('contentHelper');
        $this->loginHelper = $this->loadModel('loginHelper');
        $this->userHelper = $this->loadModel('userHelper');
        $this->quizHelper = $this->loadModel('quizHelper');
        $this->models = $this->loadModel('mkursus');
	}
	
	function index(){
    	//return $this->loadView('gallery/gallery');
    }
    
    function local()
    {
        if (isset($_POST['token'])){

            $validateData = $this->loginHelper->local($_POST);
            if ($validateData){
                $_SESSION['user'] = $validateData;
                print json_encode(array('status'=>true));
            }else{
                print json_encode(array('status'=>false));
            }

        }

        exit;
    }

    function login(){

        global $basedomain;

        

    	return $this->loadView('user/login');
    }
    
    function register(){
    	return $this->loadView('user/register');
    }
    
    function register_step1(){
        global $basedomain;
        if ($_POST){
            // pr($_POST);

            $save = $this->contentHelper->createAccount($_POST);
            if ($save){
                
                redirect($basedomain.'user/register_step2');
            }
        }
    	return $this->loadView('user/register_step1');
    }
    
    function register_step2(){
        global $basedomain;

        
        $isUserSet = $_SESSION['newuser']['email'];
        if ($isUserSet=="") {redirect($basedomain.'user/register_step1');exit;}

        $getNomenklatur = $this->contentHelper->getNomenklatur();
        // pr($getNomenklatur);
        if ($_POST){
            // pr($_POST);

            $save = $this->contentHelper->updateBiodata($_POST);
            if ($save){
                
                redirect($basedomain.'user/register_step3');
            }else{
                redirect($basedomain.'user/register_step2');exit;
            }
        }

        $this->view->assign('rumpun',$getNomenklatur);
    	return $this->loadView('user/register_step2');
    }
    
    function register_step3(){

        global $basedomain;

        $isUserSet = $_SESSION['newuser']['email'];
        if ($isUserSet=="") {redirect($basedomain.'user/register_step1');exit;}


        if ($_POST){
            // pr($_POST);

            $save = $this->contentHelper->updateRiwayat($_POST);
            // pr($save);
            if ($save){
                $keberhasilan = $this->contentHelper->updateKeberhasilan($_POST);
                if ($keberhasilan){
                    redirect($basedomain.'user/register_step4');
                }else{
                    redirect($basedomain.'user/register_step3');exit;
                } 
            }else{
                redirect($basedomain.'user/register_step3');exit;
            }
        }

    	return $this->loadView('user/register_step3');
    }
    
    function register_step4(){

        
        global $basedomain;
        $isUserSet = $_SESSION['newuser']['email'];
        if ($isUserSet=="") {redirect($basedomain.'user/register_step1');exit;}

        if ($_POST){
            // pr($_POST);

            if(!empty($_FILES)){
                if($_FILES['file_image']['name'] != ''){
                    
                    $image = uploadFile('file_image',null,'image');
                    $x['image'] = $image['full_name'];

                    if ($image){
                        $save = $this->contentHelper->updatePersetujuan($image);
                        if ($save){
                            
                            redirect($basedomain.'user/register_step5');
                        }
                    }else{
                        redirect($basedomain.'user/register_step4');exit;
                    }

                }

                
                
            }else{
                redirect($basedomain.'user/register_step4');exit;
            }

            exit;
            
        }
    	return $this->loadView('user/register_step4');
    }

    function register_step5(){

        global $basedomain;

        $isUserSet = $_SESSION['newuser']['email'];
        if ($isUserSet=="") {redirect($basedomain.'user/register_step1');exit;}

        return $this->loadView('user/register_step5');
    }

    function ajax()
    {
        $email = _p('email');

        $validate = $this->userHelper->validateEmail($email);
        if ($validate){

            print json_encode(array('status'=>true));
        }else{
            print json_encode(array('status'=>false));
        }

        exit;
    }

    function setting(){

        $profile = $this->userHelper->getUserData('idUser', $this->user['idUser']);
        
        $quiz = $this->quizHelper->getNilaiByProfile();
        // pr($profile);
        // db($quiz);
        if ($quiz){
            foreach ($quiz as $key => $value) {
                $groupid[$value['idGroupKursus']][$value['idNilai']] = $value['nilai'];
            }
            // pr($groupid);
            if ($groupid){
                foreach ($groupid as $key => $value) {
                    $max = max($value);

                    $searchKey = array_search($max, $value);

                    $maxRes[$key]= $searchKey;
                }
            }
            // pr($maxRes);

            foreach ($quiz as $key => $value) {

                if (in_array($value['idNilai'], $maxRes)){

                    $newData[] = $value;
                }
            }
            // pr($newData);
        }

        $attempt = $this->models->get_attempt_user($value['idGroupKursus'],$this->user['idUser']);
        
        $this->view->assign('attempts',$attempt['jml']);
        $this->view->assign('profile',$profile);
        $this->view->assign('quiz',$newData);
        return $this->loadView('akun/setting');
    }


}

?>
