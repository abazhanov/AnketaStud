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
          $request="SELECT     anstud_main.id_prep, prep_man.fam, prep_man.imja, prep_man.otch, anstud_question.question, round(AVG(anstud_main.answer), 2) sum_answer, round(sum(man.avgg ),2) as avv  
                    FROM            anstud_main INNER JOIN
                                                    anstud_question ON anstud_main.question = anstud_question.id
						                        INNER JOIN
                                                    prep_profile ON anstud_main.id_prep = prep_profile.oid
						                        INNER JOIN
                                                    prep_man ON prep_profile.prep = prep_man.oid 
													
													inner join (select id_prep, AVG(anstud_main.answer) as avgg from anstud_main
													
													INNER JOIN
                                                    anstud_question ON anstud_main.question = anstud_question.id
						                        INNER JOIN
                                                    prep_profile ON anstud_main.id_prep = prep_profile.oid
						                        INNER JOIN
                                                    prep_man ON prep_profile.prep = prep_man.oid 
													group by id_prep) as man on anstud_main.id_prep=man.id_prep

						 GROUP BY anstud_main.id_prep, prep_man.fam, prep_man.imja, prep_man.otch, anstud_question.question
						 ORDER BY avv desc";

            $res=sqlsrv_query($conn, $request);
            $pid=0; //проверка текущего ФИО и первой записи(0 означает что это первая запись и тогда не добавляется тег <hr>)
            $DetailRecord="";
            echo "<div class=\"panel-group\" id=\"accordion\" role=\"tablist\" aria-multiselectable=\"true\">";
            while( $obj = sqlsrv_fetch_object($res)) {
                if($pid !=0 and $pid != $obj->id_prep) {

                    //echo "<hr>";
                    //echo"<h2>$obj->fam $obj->imja $obj->otch (Рейтинг: $obj->avv)</h2>";

                    echo "<div class=\"panel panel-default\">
                            <div class=\"panel-heading\" role=\"tab\" id=\"heading$pid\">
                              <h4 class=\"panel-title\">
                                <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse$pid\" aria-expanded=\"true\" aria-controls=\"collapse$pid\">
                                  $LastFIO
                                </a>
                              </h4>
                            </div>
                            <div id=\"collapse$pid\" class=\"panel-collapse collapse\" role=\"tabpanel\" aria-labelledby=\"heading$pid\">
                              <div class=\"panel-body\">
                                $DetailRecord
                              </div>
                            </div>
                          </div>";



                    //echo"<h2>$obj->fam $obj->imja $obj->otch (Рейтинг: $obj->avv)</h2>";
                    $DetailRecord="";

                }

                $DetailRecord=$DetailRecord."<p>$obj->question: $obj->sum_answer</p>";

                $pid=$obj->id_prep;
                $LastFIO="$obj->fam $obj->imja $obj->otch (Рейтинг: $obj->avv)";

            }

                                echo "<div class=\"panel panel-default\">
                            <div class=\"panel-heading\" role=\"tab\" id=\"heading$pid\">
                              <h4 class=\"panel-title\">
                                <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse$pid\" aria-expanded=\"true\" aria-controls=\"collapse$pid\">
                                  $LastFIO
                                </a>
                              </h4>
                            </div>
                            <div id=\"collapse$pid\" class=\"panel-collapse collapse\" role=\"tabpanel\" aria-labelledby=\"heading$pid\">
                              <div class=\"panel-body\">
                                $DetailRecord
                              </div>
                            </div>
                          </div>";

 
                         
          echo "</div>";
          
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