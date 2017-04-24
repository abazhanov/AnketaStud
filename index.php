<!DOCTYPE html>
<html>
  <head>
      <meta charset="windows-1251">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Преподаватель глазами студентов</title>






    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
     <link href="css/dopstyle.css" rel="stylesheet">
  </head>
  <body>
  
    <div class="container">
          <h1>Преподаватель глазами студентов</h1>

      <?php
      header("Content-Type: text/html; charset=cp1251");
      
      $UID="viewer"; 
      $PWD="qaz123"; 
      $serverName = "172.17.3.7"; 
      $connectionInfo = array( "Database"=>"HS", "UID"=>"$UID", "PWD"=>"$PWD"); 
      $conn = sqlsrv_connect( $serverName, $connectionInfo); 

      if(!$conn) die(print_r (sqlsrv_errors(),true));    

      $request="SELECT oid, kod FROM gruppa WHERE sub<>1 ORDER BY kod";
      $res=sqlsrv_query($conn, $request);
      //$row=sqlsrv_fetch_object($res);
      ?>


      <!-- Single button -->
      <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Выберети группу <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
    
      <?php
      while( $obj = sqlsrv_fetch_object($res)) {
        echo "<li><a href=\"sel_prep.php?id=".$obj->oid."\">".$obj->kod."</a></li>";
        //echo $obj->oid."---";
       
      }
      ?>
        </ul>
      </div>



    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
      $(function () {
        $('[data-toggle="popover"]').popover()
      })
    </script>


    
  </body>
</html>