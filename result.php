<!DOCTYPE html>
<html>
  <head>
   
  <meta charset="windows-1251">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>������������� ������� ���������</title>
      <!-- Bootstrap -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="css/dopstyle.css" rel="stylesheet">
  </head>
  <body>

    <div class="container">
        <h1>������������� ������� ���������</h1>
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
        <!-- ���������� ��� -->
        <?php
          //�������� �����������
          foreach($_POST as $key => $value) {
                
                $NV_Input=explode("&&&",$key);
                
                echo "POST ������:".$NV_Input[0]." | id �������������: ".$NV_Input[1]." | �������� ������: ".$value." | ID ����������: ".$NV_Input[2]."<br>";

                $question="INSERT anstud_main (id_prep, answer, date, question, id_disciplina) values ($NV_Input[1],$value,GETDATE(), $NV_Input[0], $NV_Input[2])";
                $res_questions=sqlsrv_query($conn, $question);
                if( $res_questions === false ) {
                    die( print_r( sqlsrv_errors(), true));
                }               
                //echo "<br>��������� �������:". $res_questions."<br>";
          }  
          echo "<h3>�������, ���� ������ ������!</h3>";
          
        ?>





    </div>
    
    <br>
    <footer class="footer">
      <div class="container">
        <p class="text-muted">������������� ������� ���������</p>
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