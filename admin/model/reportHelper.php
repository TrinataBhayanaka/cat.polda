<?php

class reportHelper extends Database {
	
	var $prefix = "";
	var $salt = "";
    var $configkey = "admin";
    //var $pdf_ext = '.pdf';
	function __construct()
	{

        error_reporting(E_ALL ^ E_NOTICE);

		$loadSession = new Session();
        $getUserData = $loadSession->get_session();
        $this->user = $getUserData[0];
		$this->salt = "ovancop1234";
		$this->token = str_shuffle('cmsaj23y4ywdni237yeisa');
		$this->date = date('Y-m-d H:i:s');

        
	}  

    function loadMpdf($html, $output=null,$param=1)
    {
		// echo "masuk";
		// echo $output;
		// exit;
        
        global $CONFIG;
		$pdf_ext = '.pdf';
        $mpdfEngine = '../' . LIBS . 'mpdf/mpdf' . $CONFIG[$this->configkey]['php_ext'];

        if (is_file($mpdfEngine)){
            
            require_once ($mpdfEngine);
            if($param == 1){
				$mpdf=new mPDF('c','A4','','',15,15,16,16,9,9,'L'); 
				$mpdf->SetDisplayMode('fullpage');
            
			}elseif($param == 2){
				$mpdf=new mPDF('','','','',15,15,16,16,9,9,'P');
				$mpdf->AddPage('L','','','','',15,15,16,16,9,9);
			
			}
			// $mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 
            // $mpdf->setFooter('{PAGENO}') ;
			
			$mpdf->WriteHTML($html);
			$mpdf->Output($output . '-' . date(ymdhis) . $pdf_ext,'D');
            
            logFile('load excel success');
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