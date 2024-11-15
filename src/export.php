<?php 

function connecttooracle(){
  $db = "(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 172.25.1.172)(PORT = 1521))
    )
    (CONNECT_DATA = (SID=clty))
  )" ;
  
    //open connection
    if($c = oci_connect("ossprg", "prgoss456", $db))
    {
        return $c;
    }
    else
    {
        $err = OCIError();
        echo "Connection failed." . $err[text];
    }
}

$CON = connecttooracle(); 

include('../assets/Classes/PHPExcel.php');

$objPHPExcel	=	new	PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'FF_ID');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'CUS_LATITUDE');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'CUS_LONGITUDE');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'VOICE_NO');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'CUS_CR');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'CUS_ACCOUNT');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'MOBILE_NO');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'SALES_CATAGORY');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'CUS_CATAGORY');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'FDP');
$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'ENTERD_BY');
$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'ENTERTED_ON');
$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'D_TV');
$objPHPExcel->getActiveSheet()->SetCellValue('N1', 'D_BB');
$objPHPExcel->getActiveSheet()->SetCellValue('O1', 'D_TV_AND_D_BB');
$objPHPExcel->getActiveSheet()->SetCellValue('P1', 'SAT_TV');
$objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'OTHER');
$objPHPExcel->getActiveSheet()->SetCellValue('R1', 'SLT_SERVICE1');
$objPHPExcel->getActiveSheet()->SetCellValue('S1', 'SLT_SERVICE1_SATISFACTION');
$objPHPExcel->getActiveSheet()->SetCellValue('T1', 'SLT_SERVICE2');
$objPHPExcel->getActiveSheet()->SetCellValue('U1', 'SLT_SERVICE2_SATISFACTION');
$objPHPExcel->getActiveSheet()->SetCellValue('V1', 'SLT_SERVICE3');
$objPHPExcel->getActiveSheet()->SetCellValue('W1', 'SLT_SERVICE3_SATISFACTION');
$objPHPExcel->getActiveSheet()->SetCellValue('X1', 'SLT_SERVICE4');
$objPHPExcel->getActiveSheet()->SetCellValue('Y1', 'SLT_SERVICE4_SATISFACTION');
$objPHPExcel->getActiveSheet()->SetCellValue('Z1', 'MORE_SV_EXIST');

$objPHPExcel->getActiveSheet()->getStyle("A1:B1:C1")->getFont()->setBold(true);

$rowCount	=	2;


	$sql1="SELECT FF_ID,CUS_LATITUDE,CUS_LONGITUDE,VOICE_NO,CUS_CR,CUS_ACCOUNT,MOBILE_NO,
			SALES_CATAGORY,CUS_CATAGORY,FDP,ENTERD_BY,to_char(ENTERTED_ON,'MM/DD/YYYY HH:MI:SS AM' ) ENTERTED_ON,D_TV,D_BB,D_TV_AND_D_BB,
			SAT_TV,OTHER,SLT_SERVICE1,SLT_SERVICE1_SATISFACTION,SLT_SERVICE2,SLT_SERVICE2_SATISFACTION,
			SLT_SERVICE3,SLT_SERVICE3_SATISFACTION,SLT_SERVICE4,SLT_SERVICE4_SATISFACTION,MORE_SV_EXIST
			FROM FF_REPORT2 
			ORDER BY FF_ID";

	$stid1=oci_parse($CON,$sql1);
	oci_execute($stid1);
	
	while($row = oci_fetch_array($stid1)){
	
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, mb_strtoupper($row['FF_ID'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, mb_strtoupper($row['CUS_LATITUDE'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, mb_strtoupper($row['CUS_LONGITUDE'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, mb_strtoupper($row['VOICE_NO'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, mb_strtoupper($row['CUS_CR'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, mb_strtoupper($row['CUS_ACCOUNT'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, mb_strtoupper($row['MOBILE_NO'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, mb_strtoupper($row['SALES_CATAGORY'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, mb_strtoupper($row['CUS_CATAGORY'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, mb_strtoupper($row['FDP'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, mb_strtoupper($row['ENTERD_BY'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, mb_strtoupper($row['ENTERTED_ON'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, mb_strtoupper($row['D_TV'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, mb_strtoupper($row['D_BB'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, mb_strtoupper($row['D_TV_AND_D_BB'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, mb_strtoupper($row['SAT_TV'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, mb_strtoupper($row['OTHER'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, mb_strtoupper($row['SLT_SERVICE1'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, mb_strtoupper($row['SLT_SERVICE1_SATISFACTION'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, mb_strtoupper($row['SLT_SERVICE2'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, mb_strtoupper($row['SLT_SERVICE2_SATISFACTION'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, mb_strtoupper($row['SLT_SERVICE3'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, mb_strtoupper($row['SLT_SERVICE3_SATISFACTION'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, mb_strtoupper($row['SLT_SERVICE4'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('Y'.$rowCount, mb_strtoupper($row['SLT_SERVICE4_SATISFACTION'],'UTF-8'));
	$objPHPExcel->getActiveSheet()->SetCellValue('Z'.$rowCount, mb_strtoupper($row['MORE_SV_EXIST'],'UTF-8'));
	
	$rowCount++;
	
	}

$objWriter	=	new PHPExcel_Writer_Excel2007($objPHPExcel);

header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="FiberFiesta_Full_Report.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
$objWriter->save('php://output');

?>