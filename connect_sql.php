<?php
//header("Content-Type: text/html; charset=cp1251");
//        $UID="sa"; 
//        $PWD="Gthkjdrf"; 
//        $serverName = "172.17.150.9"; 
//        $connectionInfo = array( "Database"=>"HS", "UID"=>"$UID", "PWD"=>"$PWD"); 
//        $conn = sqlsrv_connect( $serverName, $connectionInfo); 
//        if(!$conn) die(print_r (sqlsrv_errors(),true));    
?>

<?php
header("Content-Type: text/html; charset=cp1251");
        $UID="viewer"; 
        $PWD="qaz123"; 
        $serverName = "172.17.3.7"; 
        $connectionInfo = array( "Database"=>"HS_FOR_TEST", "UID"=>"$UID", "PWD"=>"$PWD"); 
        $conn = sqlsrv_connect( $serverName, $connectionInfo); 
        if(!$conn) die(print_r (sqlsrv_errors(),true));    
?>