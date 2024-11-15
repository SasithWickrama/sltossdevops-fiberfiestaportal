<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$connstring = '(DESCRIPTION =
(ADDRESS_LIST =
(ADDRESS = (PROTOCOL = TCP)(HOST = 172.25.1.172)(PORT = 1521))
)
(CONNECT_DATA = (SID=clty))
)';
$user = 'ossprg';
$pass = 'prgoss456';



try {
    $conn = new PDO("oci:dbname=" . $connstring, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $error = "Database Error: " . $e->getMessage();
    echo "<script>alert('DB Error!'); </script>";
}

$q = $_GET['q'];

if($q == '1'){
	
$SaleCat = $_GET['SaleCat'];
$FFRec = $_GET['FFRec'];

// $sql = "SELECT FF_ID,FF_LON, FF_LAT,FF_VOICENO,FF_CR,FF_ACC,FF_CATAGORY,FF_STATUS,FF_STATUSDATE,FF_USER,FF_FDP,FF_CUSCAT,FF_RTOM,
// 		CASE
// 			WHEN  LENGTH(FF_MOBILE) <= 2 THEN 'null'
// 			ELSE FF_MOBILE
// 		END AS FF_MOBILE,
// 		CASE
// 			WHEN  LENGTH(FF_VOICENO) <= 3 THEN 'null'
// 			ELSE FF_VOICENO
// 		END AS FF_VOICENO
// 		FROM FF_RECORDS WHERE FF_LON IS NOT NULL AND FF_LAT IS NOT NULL";

$sql = "SELECT FF_ID,FF_LON, FF_LAT,FF_VOICENO,FF_CR,FF_ACC,FF_CATAGORY,FF_STATUS,FF_STATUSDATE,FF_USER,FF_FDP,FF_CUSCAT,FF_RTOM,
CASE
	WHEN  LENGTH(FF_MOBILE) <= 2 THEN 'null'
	ELSE FF_MOBILE
END AS FF_MOBILE,
CASE
	WHEN  LENGTH(FF_VOICENO) <= 3 THEN 'null'
	ELSE FF_VOICENO
END AS FF_VOICENO , USR_NAME,MOBILENO
FROM FF_RECORDS , FF_APP_USERS WHERE FF_LON IS NOT NULL AND FF_LAT IS NOT NULL
and FF_USER = USR_ID
and TO_DATE (FF_STATUSDATE, 'DD.MON.YYYY') = TO_DATE (sysdate, 'DD.MON.YYYY')";

if($SaleCat != ''){
	
	$sql .= " AND FF_CATAGORY = '".$SaleCat."'";
	
}

if($FFRec != ''){
	
	$sql .= " AND FF_CUSCAT = '".$FFRec."'";
	
}

$statment = $conn->prepare($sql);
$statment->execute();
$cctdetails = $statment->fetchAll();
$returndata['datax'] = $cctdetails;

}

if($q == '2'){

$cdate = date('Y-m-d');
// $sql = "SELECT SID,USR_NAME, LAT, LON, TO_CHAR(LOG_DATE,'YYYY-MM-DD HH24:MI:SS') as LOG_DATE
// 	  FROM ( SELECT a.SID,b.USR_NAME, a.LAT, a.LON, a.LOG_DATE, MAX(a.LOG_DATE) OVER (partition by a.SID) LATEST_DATE FROM FF_USER_LOCATIONS a,FF_APP_USERS b where A.SID = B.USR_ID)
// 	  WHERE LOG_DATE = LATEST_DATE
// 	  AND TO_CHAR(LOG_DATE,'YYYY-mm-dd') = '".$cdate."'
// 	  AND SID IS NOT NULL
// 	  AND LAT != 'null'
// 	  AND LON != 'null'";


$sql = "SELECT SID,USR_NAME, LAT, LON, TO_CHAR(LOG_DATE,'YYYY-MM-DD HH24:MI:SS') as LOG_DATE , (sysdate - LOG_DATE ) * 24 *60  UTIME 
FROM ( SELECT a.SID,b.USR_NAME, a.LAT, a.LON, a.LOG_DATE, MAX(a.LOG_DATE) OVER (partition by a.SID) LATEST_DATE FROM FF_USER_LOCATIONS a,FF_APP_USERS b where A.SID = B.USR_ID)
WHERE LOG_DATE = LATEST_DATE
AND TO_CHAR(LOG_DATE,'YYYY-mm-dd') = '$cdate'
AND SID IS NOT NULL
AND LAT != 'null'
AND LON != 'null'";

$statment = $conn->prepare($sql);
$statment->execute();
$cctdetails = $statment->fetchAll();
$returndata['datax'] = $cctdetails;

}

if($q == '3'){

$FF_ID = $_GET['FF_ID'];

$sql = "SELECT * from FF_SERVICES WHERE FF_ID = '".$FF_ID."'";

$statment = $conn->prepare($sql);
$statment->execute();
$cctdetails1 = $statment->fetchAll();
$returndata['dataslt'] = $cctdetails1;

$sql = "SELECT * from FF_OTHERSV WHERE FF_ID = '".$FF_ID."'";

$statment = $conn->prepare($sql);
$statment->execute();
$cctdetails2 = $statment->fetchAll();
$returndata['dataNslt'] = $cctdetails2;

}


if($q == '4'){

	$cdate = date('Y-m-d');
	$searchuser = $_GET['searchuser'];
	
	$sql = "SELECT SID,USR_NAME, LAT, LON, TO_CHAR(LOG_DATE,'YYYY-MM-DD HH24:MI:SS') as LOG_DATE , (sysdate - LOG_DATE ) * 24 *60  UTIME 
	FROM ( SELECT a.SID,b.USR_NAME, a.LAT, a.LON, a.LOG_DATE, MAX(a.LOG_DATE) OVER (partition by a.SID) LATEST_DATE FROM FF_USER_LOCATIONS a,FF_APP_USERS b where A.SID = B.USR_ID)
	WHERE  TO_CHAR(LOG_DATE,'YYYY-mm-dd') = '$cdate'
	AND SID = '$searchuser'
	AND LAT != 'null'
	AND LON != 'null'
	order by LOG_DATE";
	
	$statment = $conn->prepare($sql);
	$statment->execute();
	$cctdetails = $statment->fetchAll();
	$returndata['datax'] = $cctdetails;
	
	}

$statment->closeCursor();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($returndata);
