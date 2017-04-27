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



            //Старый запрос
            $old_request="SELECT     id_prep, prep_man.fam, prep_man.imja, prep_man.otch, anstud_question.question, AVG(anstud_main.answer) sum_answer   
                    FROM            anstud_main INNER JOIN
                                                    anstud_question ON anstud_main.question = anstud_question.id
						                        INNER JOIN
                                                    prep_profile ON anstud_main.id_prep = prep_profile.oid
						                        INNER JOIN
                                                    prep_man ON prep_profile.prep = prep_man.oid
						 GROUP BY id_prep, prep_man.fam, prep_man.imja, prep_man.otch, anstud_question.question
						 ORDER BY prep_man.fam, anstud_question.question";
             ///////////////////////////////


            $res=sqlsrv_query($conn, $request);
            $pid=0; //проверка текущего ФИО и первой записи(0 означает что это первая запись и тогда не добавляется тег <hr>)
            $DetailRecord="";
            while( $obj = sqlsrv_fetch_object($res)) {
                if($pid !=0 and $pid != $obj->id_prep) {

                    echo "<div class=\"collapse\" id=\"collapseExample$pid\">
                            <div class=\"well\">
                              $DetailRecord
                            </div>
                          </div>";

                    $DetailRecord="";

                    echo "<hr>";
                    echo"<h2>$obj->fam $obj->imja $obj->otch (Рейтинг: $obj->avv)</h2>";
                    echo "<a class=\"btn btn-primary\" role=\"button\" data-toggle=\"collapse\" href=\"#collapseExample$obj->id_prep\" aria-expanded=\"false\" aria-controls=\"collapseExample\">
                      Показать детальные записи
                      </a> ";
                    echo "<button class=\"btn btn-primary\" type=\"button\" data-toggle=\"collapse\" data-target=\"#collapseExample$obj->id_prep\" aria-expanded=\"false\" aria-controls=\"collapseExample\">
                          Скрыть детальные записи
                          </button>";
                } elseif($pid==0){
                    echo"<h2>$obj->fam $obj->imja $obj->otch (Рейтинг: $obj->avv)</h2>";
                    echo "<a class=\"btn btn-primary\" role=\"button\" data-toggle=\"collapse\" href=\"#collapseExample$obj->id_prep\" aria-expanded=\"false\" aria-controls=\"collapseExample\">
                      Показать детальные записи
                      </a> ";
                    echo "<button class=\"btn btn-primary\" type=\"button\" data-toggle=\"collapse\" data-target=\"#collapseExample$obj->id_prep\" aria-expanded=\"false\" aria-controls=\"collapseExample\">
                          Скрыть детальные записи
                          </button>";
                }

                $DetailRecord=$DetailRecord."<p>$obj->question: $obj->sum_answer</p>";

                $pid=$obj->id_prep;

            }

                             echo "<div class=\"collapse\" id=\"collapseExample$pid\">
                            <div class=\"well\">
                              $DetailRecord
                            </div>
                          </div>";  
                         
          
          
        ?>






<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Collapsible Group Item #1
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Collapsible Group Item #2
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Collapsible Group Item #3
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>
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