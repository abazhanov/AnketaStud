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
        <!-- -->
        <?php
          $request="SELECT oid, kod FROM gruppa WHERE sub<>1 ORDER BY kod";
          $res=sqlsrv_query($conn, $request);
        ?>
          
        <!-- Single button -->
        <div class="btn-group">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Выбор группы <span class="caret"></span>
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


        <div>
        <br>
        <h2>Оцените Ваших преподавателей:</h2>

      
          <!-- Get FIO prepod -->
          <?php   

            $request="SELECT   DISTINCT     prep_man.fam, prep_man.imja, prep_man.otch, zplan.gruppa, prep_profile.oid pid
FROM            zplan INNER JOIN
                         pps ON zplan.pps = pps.oid INNER JOIN
                         prep_profile ON pps.prep = prep_profile.oid INNER JOIN
                         prep_man ON prep_profile.prep = prep_man.oid
WHERE        (zplan.gruppa = ".$_GET['id'].")";

            $res=sqlsrv_query($conn, $request);


            //Получаю вопросы и их id
            $questions="SELECT        id, question
                        FROM            anstud_question";
            $res_questions=sqlsrv_query($conn, $questions);

            ?>

            <!-- Формируем таблицу преподавателей внутри формы -->
            <form action="result.php" method="post">

     
              
    
            <?php
              while( $obj = sqlsrv_fetch_object($res)) { //Перебираем преподавателей
              echo "<h3>".$obj->fam." ".$obj->imja." ".$obj->otch."</h3>";
              echo "<h2>".$obj->pid."</h3>";
              echo "<table class=\"table\">
                      <thead>
                        <tr>
                          <th>Критерий</th>
                          <th>Очень плохо</th>
                          <th>Плохо</th>
                          <th>Нормально</th>
                          <th>Хорошо</th>
                          <th>Очень хорошо</th>
                      </tr>
                    </thead>
                    <tbody>";
                        while( $obj_questions = sqlsrv_fetch_object($res_questions)) { //Перебираем вопросы
                      
                  echo "<tr>
                        <td>".$obj_questions->question."</td> 
                        <td align=\"center\"><input type=\"radio\" name=\"$obj_questions->id&&&$obj->pid\" value=\"1\"></td>
                        <td align=\"center\"><input type=\"radio\" name=\"$obj_questions->id&&&$obj->pid\" value=\"2\"></td>
                        <td align=\"center\"><input type=\"radio\" name=\"$obj_questions->id&&&$obj->pid\" value=\"3\"></td>
                        <td align=\"center\"><input type=\"radio\" name=\"$obj_questions->id&&&$obj->pid\" value=\"4\"></td>
                        <td align=\"center\"><input type=\"radio\" name=\"$obj_questions->id&&&$obj->pid\" value=\"5\"></td>
                      </tr>";
                      
                        }

                  echo"</tbody>
                  </table>";
                  $res_questions=sqlsrv_query($conn, $questions);
                        
              }
            ?>

              
            <input type="submit">
        </form>
          
    </div>
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