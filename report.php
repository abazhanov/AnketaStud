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
          //Создаем отчет по преподавателям
          $request="SELECT     id_prep, prep_man.fam, prep_man.imja, prep_man.otch, anstud_question.question, SUM(anstud_main.answer) sum_answer   
                    FROM            anstud_main INNER JOIN
                                                    anstud_question ON anstud_main.question = anstud_question.id
						                        INNER JOIN
                                                    prep_profile ON anstud_main.id_prep = prep_profile.oid
						                        INNER JOIN
                                                    prep_man ON prep_profile.prep = prep_man.oid
						 GROUP BY id_prep, prep_man.fam, prep_man.imja, prep_man.otch, anstud_question.question
						 ORDER BY prep_man.fam, anstud_question.question";
            $res=sqlsrv_query($conn, $request);
            $pid=0;
            while( $obj = sqlsrv_fetch_object($res)) {
                if($pid !=0 and $pid != $obj->id_prep) {
                    echo "<hr>";
                    echo"<h2>$obj->fam $obj->imja $obj->otch</h2>";
                } elseif($pid==0){
                    echo"<h2>$obj->fam $obj->imja $obj->otch</h2>";
                }
                echo "<p>$obj->question: $obj->sum_answer</p>";
                $pid=$obj->id_prep;
            }
                         
          
          
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