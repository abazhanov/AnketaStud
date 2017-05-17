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
        <!-- Open link with DB -->
        <?php
          header("Content-Type: text/html; charset=cp1251");
          $UID="viewer"; 
          $PWD="qaz123"; 
          $serverName = "172.17.3.7"; 
          $connectionInfo = array( "Database"=>"HS", "UID"=>"$UID", "PWD"=>"$PWD"); 
          $conn = sqlsrv_connect( $serverName, $connectionInfo); 
          if(!$conn) die(print_r (sqlsrv_errors(),true));
        ?>
        <!-- Уникальный код -->
        <?php
          //Запись результатов
          //echo "Значение key: ".$_POST['id_user'];
          $FirstValue=1;
          foreach($_POST as $key => $value) {
                //echo "Значение key: ".$key;
                if($FirstValue==1) {
                  $FirstValue=0;
                  $id_user=$_POST['id_user'];
                }
                else {
                  $NV_Input=explode("&&&",$key);
                  echo "POST Вопрос:".$NV_Input[0]." | id преподавателя: ".$NV_Input[1]." | значение ответа: ".$value." | ID Дисциплины: ".$NV_Input[2]."<br>";
                  $question="INSERT anstud_main (id_prep, answer, date, question, id_disciplina) values ($NV_Input[1],$value,GETDATE(), $NV_Input[0], $NV_Input[2])";
                  $res_questions=sqlsrv_query($conn, $question);
                  if( $res_questions === false ) {
                    die( print_r( sqlsrv_errors(), true));
                  }
                }

          $request="UPDATE anstud_login SET Used=1 WHERE id=".$id_user;
          $res=sqlsrv_query($conn, $request);
          if( $res === false ) {
            die( print_r( sqlsrv_errors(), true));
          }
          

                
          }  
          echo "<h3>Спасибо, Ваше мнение учтено!</h3>";
          
        ?>





    </div>
    
    <br>
    <footer class="footer">
      <div class="container">
        <p class="text-muted">Преподаватель глазами студентов</p>
      </div>
    </footer>





    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
      $(function () {
        $('[data-toggle="popover"]').popover()
      })
    </script>


  </body>
</html>