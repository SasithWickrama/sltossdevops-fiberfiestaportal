<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
include 'dbcon.php';

if (!$CON) 
{
  die('Not connected : ' );
}

$uname = $_POST['login']."@intranet.slt.com.lk";
$pwd = $_POST['password'];

$_SESSION['$usrid']= $_POST['login'];

$link = ldap_connect( 'intranet.slt.com.lk' );

if(! $link )
{
	echo"Cant Connect to Server";
}

ldap_set_option($link, LDAP_OPT_PROTOCOL_VERSION, 3); 

if (ldap_bind( $link, $uname, $pwd ) )
{
	$CON = connecttooracle();
	$sql = "select * from OSSPRG.FF_APP_USERS  where USR_ID ='".$_POST['login']."'";

	$userid = oci_parse($CON, $sql);
	oci_execute($userid);
	$row= oci_fetch_array($userid);

   	if($row['USR_ID'] != '' && $row['ADMINL'] == 'Y'){

	 $_SESSION['$usrname']= $row['USR_ID'];
	 $_SESSION['loggedin'] = true;
	 $_SESSION['app'] = 'FF';
	 

		echo '<script type="text/javascript"> document.location = "customer_location.php";</script>';

	}else{
		
		echo "<script type='text/javascript'>alert('Not Authorize for this Site')</script>";
		echo '<script type="text/javascript"> document.location = "../index.php";</script>';
	}

}else{
		echo "<script type='text/javascript'>alert('Invalid User Name or Password')</script>";
		echo '<script type="text/javascript"> document.location = "../index.php";</script>';
}


?>