<?php
header("Content-Type: text/html; charset=cp1251");
        $UID="sa"; 
        $PWD="Gthkjdrf"; 
        $serverName = "172.17.150.9"; 
        $connectionInfo = array( "Database"=>"HS", "UID"=>"$UID", "PWD"=>"$PWD"); 
        $conn = sqlsrv_connect( $serverName, $connectionInfo); 
        if(!$conn) die(print_r (sqlsrv_errors(),true));    
?>