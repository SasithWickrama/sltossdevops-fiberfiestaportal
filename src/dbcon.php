<?php

function connecttouatoracle(){
    $db = "   (DESCRIPTION=
    (ADDRESS=
      (PROTOCOL=TCP)
      (HOST=172.25.16.192)
      (PORT=1599)
    )
    (CONNECT_DATA=
      (SERVICE_NAME=clty_uat)
    )
  )
";
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


/*function connecttooracle(){
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
}*/

function connecttooracle(){
  $db = "(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 172.25.1.172)(PORT = 1521))
    )
    (CONNECT_DATA = (SID=clty))
	)" ;
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

?>