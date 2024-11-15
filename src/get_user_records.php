<?php

error_reporting(E_ALL);
session_start();

include 'dbcon.php';

if (!$CON) 
{
  die('Not connected : ' );
}
        
        $curbdate = date('Y/m/d');

        $sql = "SELECT FF_ID,CUS_LATITUDE,CUS_LONGITUDE,VOICE_NO,CUS_CR,CUS_ACCOUNT,MOBILE_NO,
                SALES_CATAGORY,CUS_CATAGORY,FDP,ENTERD_BY,to_char(ENTERTED_ON,'MM/DD/YYYY HH:MI:SS AM' ) ENTERTED_ON,D_TV,D_BB,D_TV_AND_D_BB,
                SAT_TV,OTHER,SLT_SERVICE1,SLT_SERVICE1_SATISFACTION,SLT_SERVICE2,SLT_SERVICE2_SATISFACTION,
                SLT_SERVICE3,SLT_SERVICE3_SATISFACTION,SLT_SERVICE4,SLT_SERVICE4_SATISFACTION,MORE_SV_EXIST
                FROM FF_REPORT1 
                ORDER BY FF_ID";

        $stid = oci_parse($CON, $sql);
        oci_execute($stid);

        // $res = oci_fetch($stid);
        $data = [];

        while(oci_fetch($stid)){     
        
           // echo 'sss';
        // }

            
        //     var_dump($res);

        //     foreach($res as $value) {
                $data[] = [
                    'FF_ID' => oci_result($stid,oci_field_name($stid, 'FF_ID')),
                    'VOICE_NO' => oci_result($stid,oci_field_name($stid, 'VOICE_NO')),
                    'CUS_CR' => oci_result($stid,oci_field_name($stid, 'CUS_CR')),
                    'CUS_ACCOUNT' => oci_result($stid,oci_field_name($stid, 'CUS_ACCOUNT')),
                    'MOBILE_NO' => oci_result($stid,oci_field_name($stid, 'MOBILE_NO')),
                    'SALES_CATAGORY' => oci_result($stid,oci_field_name($stid, 'SALES_CATAGORY')),
                    'CUS_CATAGORY' => oci_result($stid,oci_field_name($stid, 'CUS_CATAGORY')),
                    'FDP' => oci_result($stid,oci_field_name($stid, 'FDP')),
                    'ENTERD_BY' => oci_result($stid,oci_field_name($stid, 'ENTERD_BY')),
                    'ENTERTED_ON' => oci_result($stid,oci_field_name($stid, 'ENTERTED_ON')),
                    'CUS_LATITUDE' => oci_result($stid,oci_field_name($stid, 'CUS_LATITUDE')),
                    'CUS_LONGITUDE' => oci_result($stid,oci_field_name($stid, 'CUS_LONGITUDE')),
                    'D_TV' => oci_result($stid,oci_field_name($stid, 'D_TV')),
                    'D_BB' => oci_result($stid,oci_field_name($stid, 'D_BB')),
                    'D_TV_AND_D_BB' => oci_result($stid,oci_field_name($stid, 'D_TV_AND_D_BB')),
                    'SAT_TV' => oci_result($stid,oci_field_name($stid, 'SAT_TV')),
                    'OTHER' => oci_result($stid,oci_field_name($stid, 'OTHER')),
                    'SLT_SERVICE1' => oci_result($stid,oci_field_name($stid, 'SLT_SERVICE1')),
                    'SLT_SERVICE1_SATISFACTION' => oci_result($stid,oci_field_name($stid, 'SLT_SERVICE1_SATISFACTION')),
                    'SLT_SERVICE2' => oci_result($stid,oci_field_name($stid, 'SLT_SERVICE2')),
                    'SLT_SERVICE2_SATISFACTION' => oci_result($stid,oci_field_name($stid, 'SLT_SERVICE2_SATISFACTION')),
                    'SLT_SERVICE3' => oci_result($stid,oci_field_name($stid, 'SLT_SERVICE3')),
                    'SLT_SERVICE3_SATISFACTION' => oci_result($stid,oci_field_name($stid, 'SLT_SERVICE3_SATISFACTION')),
                    'SLT_SERVICE4' => oci_result($stid,oci_field_name($stid, 'SLT_SERVICE4')),
                    'SLT_SERVICE4_SATISFACTION' => oci_result($stid,oci_field_name($stid, 'SLT_SERVICE4_SATISFACTION')),
                    'MORE_SV_EXIST' => oci_result($stid,oci_field_name($stid, 'MORE_SV_EXIST')),
                ];
            }

            $response_data['data'] = $data;




echo json_encode($response_data)

?>