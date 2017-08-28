<?php include('header.php'); ?>
    <div class="container">
        <h1>Преподаватель глазами студентов</h1>
        <?php
          include('check.php'); //Проверка атунтификации.
          if($Auth==0) { header("Location: notauth.php"); exit();  }


          /////////////////////////////////
          //Создаем отчет по преподавателям

          $KAF=$_GET['kaf'];
          if($KAF!=0) { //Если передали id кафедры, то выполняем запрос с фильтром по кафедре
          $request=" SELECT     anstud_main.id_prep, prep_man.fam, prep_man.imja, prep_man.otch, anstud_question.question, round(AVG(anstud_main.answer), 2) sum_answer, round(AVG(man.avgg ),2) as avv  
                    FROM            anstud_main 
                    INNER JOIN anstud_question ON anstud_main.question = anstud_question.id
                    INNER JOIN prep_man ON anstud_main.id_prep = prep_man.oid  
						        INNER JOIN prep_profile ON prep_profile.prep = prep_man.oid													
										inner join (select id_prep, AVG(anstud_main.answer) as avgg from anstud_main
											INNER JOIN anstud_question ON anstud_main.question = anstud_question.id
						          INNER JOIN prep_profile ON anstud_main.id_prep = prep_profile.prep
						          INNER JOIN prep_man ON prep_profile.prep = prep_man.oid 
											group by id_prep) as man on anstud_main.id_prep=man.id_prep
                    INNER JOIN preppodr ON anstud_main.id_prep = preppodr.prep	
                    INNER JOIN kafedry ON preppodr.kafedry = kafedry.oid	
                    WHERE kafedry.oid=".$KAF."
						        GROUP BY anstud_main.id_prep, prep_man.fam, prep_man.imja, prep_man.otch, anstud_question.question
						        ORDER BY avv desc, fam";
          } else { //Если не передали id кафедры, то выполняем запрос без фильтра по кафедре
          $request=" SELECT     anstud_main.id_prep, prep_man.fam, prep_man.imja, prep_man.otch, anstud_question.question, round(AVG(anstud_main.answer), 2) sum_answer, round(AVG(man.avgg ),2) as avv  
                    FROM            anstud_main 
                    INNER JOIN anstud_question ON anstud_main.question = anstud_question.id
                    INNER JOIN prep_man ON anstud_main.id_prep = prep_man.oid  
						        INNER JOIN prep_profile ON prep_profile.prep = prep_man.oid													
										inner join (select id_prep, AVG(anstud_main.answer) as avgg from anstud_main
											INNER JOIN anstud_question ON anstud_main.question = anstud_question.id
						          INNER JOIN prep_profile ON anstud_main.id_prep = prep_profile.prep
						          INNER JOIN prep_man ON prep_profile.prep = prep_man.oid 
											group by id_prep) as man on anstud_main.id_prep=man.id_prep
                    INNER JOIN preppodr ON anstud_main.id_prep = preppodr.prep	
                    INNER JOIN kafedry ON preppodr.kafedry = kafedry.oid	
                 
						        GROUP BY anstud_main.id_prep, prep_man.fam, prep_man.imja, prep_man.otch, anstud_question.question
						        ORDER BY avv desc, fam";  
          }
            $res=sqlsrv_query($conn, $request);
            $pid=0; //проверка текущего ФИО и первой записи(0 означает что это первая запись и тогда не добавляется тег <hr>)
            $DetailRecord="";
            $SumValueAnswerForPrepod=0; //Подсчитываем количество вопросов
            $SumPrepod=0; //Подсчитываем количество преподавателей
            echo "<div class=\"panel-group\" id=\"accordion\" role=\"tablist\" aria-multiselectable=\"true\">";
            while( $obj = sqlsrv_fetch_object($res)) {  //В цикле формируем раскрывающиеся вкладки
                //Логика: while перебирает строки запроса. Как только if понимает что мы перескочили на нового преподавателя, то формируем вкладку, а внутри вкладки выводим сформированную в цикле таблицу результатов
                if($pid !=0 and $pid != $obj->id_prep) {
                    echo "<div class=\"panel panel-default\">
                            <div class=\"panel-heading\" role=\"tab\" id=\"heading$pid\">
                              <h4 class=\"panel-title\">
                                <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse$pid\" aria-expanded=\"true\" aria-controls=\"collapse$pid\">
                                  $LastFIO</a><span> </span>
                                <span class=\"glyphicon glyphicon-signal\" aria-hidden=\"true\"></span> <span>$LastFIORating</span><span class=\"pull-right\"><a href=detail.php?id=".$pid." type=\"button\" class=\"btn btn-default btn-xs\">Детализация</a></span>
                              </h4>
                            </div>
                            <div id=\"collapse$pid\" class=\"panel-collapse collapse\" role=\"tabpanel\" aria-labelledby=\"heading$pid\">
                              <div class=\"panel-body\">
                                <table class=\"table table-hover\">
                                  <thead>
                                    <tr>
                                      <th>Наименование вопроса</th>
                                      <th>Средний балл</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    $DetailRecord
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>";
                    $DetailRecord="";
                    $SumValueAnswerForPrepod=0;
                    $SumPrepod++; //Раз мы попали в блок if, значит мы перескочили на нового преподавателя
                }
                $DetailRecord=$DetailRecord."<tr>
                                              <td>$obj->question</td>
                                              <td>$obj->sum_answer</td>
                                              </tr>"; //Формируем таблицу с результатами
                //Массив со значениями вопросов и ответов для формирования графика                                              
                $ValueAnswerForPrepod[$SumPrepod][$SumValueAnswerForPrepod][0]=$obj->question;
                $ValueAnswerForPrepod[$SumPrepod][$SumValueAnswerForPrepod][1]=$obj->sum_answer;
                $ValueAnswerForPrepod[$SumPrepod][$SumValueAnswerForPrepod][2]="$obj->fam $obj->imja $obj->otch";
                $SumValueAnswerForPrepod++; //Подсчет количества вопросов.
                $pid=$obj->id_prep;
                $LastFIO="$obj->fam $obj->imja $obj->otch";
                $LastFIORating=$obj->avv;
            }

            if($pid != 0) {
              //После завершения цикла еще раз выводим последнюю вкладку, т.к. из цикла мы уже вылетели, а последняя запись осталась не обработанной.
                                echo "<div class=\"panel panel-default\">
                            <div class=\"panel-heading\" role=\"tab\" id=\"heading$pid\">
                              <h4 class=\"panel-title\">
                                <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse$pid\" aria-expanded=\"true\" aria-controls=\"collapse$pid\">
                                  $LastFIO</a><span> </span>
                                  <span class=\"glyphicon glyphicon-signal\" aria-hidden=\"true\"></span> <span>$LastFIORating</span><span class=\"pull-right\"><a href=detail.php?id=".$pid." type=\"button\" class=\"btn btn-default btn-xs\">Детализация</a></span>
                              </h4>
                            </div>
                            <div id=\"collapse$pid\" class=\"panel-collapse collapse\" role=\"tabpanel\" aria-labelledby=\"heading$pid\">
                              <div class=\"panel-body\">
                                <table class=\"table table-hover\">
                                  <thead>
                                    <tr>
                                      <th>Наименование вопроса</th>
                                      <th>Средний балл</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    $DetailRecord
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>";
            } else { echo "<h3>Данные отсутствуют</h3>";}
          echo "</div>";
//////////////////////////////////////////////////////////////////          
//  Формируем JAVA скрипт, ответственный за рисование графика
//////////////////////////////////////////////////////////////////
echo "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>\n";
echo "<script type=\"text/javascript\">\n";
echo "  google.charts.load('current', {'packages':['line']});\n";
echo "  google.charts.setOnLoadCallback(drawChart);\n";
echo "  function drawChart() {\n";
echo "  var data = google.visualization.arrayToDataTable([\n";
        echo "['Вопрос'";
        for ($i=0; $i<$SumPrepod+1; $i++) {
          echo ", '".$ValueAnswerForPrepod[$i][0][2]."'";
        }
        echo "],";
          ///////////////////////////
          //Формируем массив значений
          for ($i = 0; $i < $SumValueAnswerForPrepod-1; $i++) {
             echo "['"   .$ValueAnswerForPrepod[0][$i][0]."'";
             for ($j = 0; $j < $SumPrepod+1; $j++) {
                echo ",".$ValueAnswerForPrepod[$j][$i][1];
             }
             echo "],";
          }

            echo "['"   .$ValueAnswerForPrepod[0][$i][0].  "'";
          for ($j = 0; $j < $SumPrepod+1; $j++) {
            echo ",".$ValueAnswerForPrepod[$j][$i][1];
          }
          //echo "['"   .$ValueAnswerForPrepod[$i][0].  "', ".$ValueAnswerForPrepod[$i][1]."]";
          echo "]]);";
          //Массив сформирован
          /////////////////////
//Продолжаем формировать JAVA скрипт
echo "  var options = {
         chart: {
           title: 'Сводный график по преподавателям',
           subtitle: 'в разрезе среднего балла'
         },
         width: 1000,
         height: 500
         };
         var chart = new google.charts.Line(document.getElementById('linechart_material'));
         chart.draw(data, google.charts.Line.convertOptions(options));
       }
       </script>";
//Закончили формировать JAVA скрипт
///////////////////////////////////        
        ?>
         <div id="linechart_material"></div> <!-- В этом месте рисуем график -->
    </div>
    <br>
<?php include('footer.php'); ?>