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
          <p>Уважаемые студенты, доступ к анкетированию осуществляется по логинам, выданным деканатом.</p>
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
      ?>
      <form action="rating.php" method="get">
        <p>Введите логин: <input type="text" name="login"></p>
        <p>Введите пароль: <input type="password" name="password"></p>
        <p><input type="submit" value="Войти"></p>
      </form>
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