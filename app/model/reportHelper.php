<?php

class reportHelper extends Database {
	
	var $prefix = "";
	var $salt = "";
    var $configkey = "default";
    // var $pdf_ext = '.pdf';
	function __construct()
	{

        error_reporting(E_ALL ^ E_NOTICE);
        parent::__construct();
		$loadSession = new Session();
        $getUserData = $loadSession->get_session();
        $this->user = $getUserData[0];
		$this->salt = "ovancop1234";
		$this->token = str_shuffle('cmsaj23y4ywdni237yeisa');
		$this->date = date('Y-m-d H:i:s');

        
	}  

    function loadMpdf($html, $output=null, $path=null)
    {
		// echo "masuk";
		// echo $output;
		// exit;
        global $CONFIG;
        $hslpath = $path."hasil/";
		$pdf_ext = '.pdf';
        $mpdfEngine = LIBS . 'mpdf/mpdf' . $CONFIG[$this->configkey]['php_ext'];

        if (is_file($mpdfEngine)){
            
            require_once ($mpdfEngine);
            $mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 
			$mpdf->SetDisplayMode('fullpage');
            
			/*$mpdf=new mPDF('','','','',15,15,16,16,9,9,'P');
            $mpdf->AddPage('L','','','','',15,15,16,16,9,9);
            $mpdf->setFooter('{PAGENO}') ;*/
			$stylesheet = file_get_contents($CONFIG['default']['root_path'].'assets/css/mpdfstyleA4.css');
            $mpdf->WriteHTML($stylesheet,1);
			$mpdf->WriteHTML($html);
			$mpdf->Output($hslpath . $output . $pdf_ext,'F');
            
            logFile('load excel success');
            return true;
        }else{
            logFile('excel lib not found');
        }

        exit;
    }

    function loadExcel($file=false)
    {
        // error_reporting(E_ALL ^ E_NOTICE);
        
        global $CONFIG, $EXCEL;
        if (!$file) return false;
        
        if (!in_array($_FILES[$file]['type'], $EXCEL[0]['filetype'])) return false;
        
        if (array_key_exists('admin', $CONFIG)){
            $this->configkey = 'admin';
        }
        if (array_key_exists('dashboard', $CONFIG)){
            $this->configkey = 'dashboard';
        }
        
        $excel = "";
        $filename = ($_FILES[$file]['tmp_name']);
        $excelEngine = LIBS . 'excel/excel_reader' . $CONFIG[$this->configkey]['php_ext'];
        if (is_file($excelEngine)){
            
            require_once ($excelEngine);
            
            $excel = new Spreadsheet_Excel_Reader($filename);

            logFile('load excel success');
        }else{
            logFile('excel lib not found');
        }
        
        return $excel;
    }
}
?>