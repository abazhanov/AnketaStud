<?php include('header.php'); ?>
    <div class="container">
        <h1>������������� ������� ���������</h1>
        <?php
          include('check.php'); //�������� �������������.
          if($Auth==0) { header("Location: notauth.php"); exit();  }


          /////////////////////////////////
          //������� ����� �� ��������������

          $KAF=$_GET['kaf'];
          if($KAF!=0) { //���� �������� id �������, �� ��������� ������ � �������� �� �������
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
          } else { //���� �� �������� id �������, �� ��������� ������ ��� ������� �� �������
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
            $pid=0; //�������� �������� ��� � ������ ������(0 �������� ��� ��� ������ ������ � ����� �� ����������� ��� <hr>)
            $DetailRecord="";
            $SumValueAnswerForPrepod=0; //������������ ���������� ��������
            $SumPrepod=0; //������������ ���������� ��������������
            echo "<div class=\"panel-group\" id=\"accordion\" role=\"tablist\" aria-multiselectable=\"true\">";
            while( $obj = sqlsrv_fetch_object($res)) {  //� ����� ��������� �������������� �������
                //������: while ���������� ������ �������. ��� ������ if �������� ��� �� ����������� �� ������ �������������, �� ��������� �������, � ������ ������� ������� �������������� � ����� ������� �����������
                if($pid !=0 and $pid != $obj->id_prep) {
                    echo "<div class=\"panel panel-default\">
                            <div class=\"panel-heading\" role=\"tab\" id=\"heading$pid\">
                              <h4 class=\"panel-title\">
                                <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse$pid\" aria-expanded=\"true\" aria-controls=\"collapse$pid\">
                                  $LastFIO</a><span> </span>
                                <span class=\"glyphicon glyphicon-signal\" aria-hidden=\"true\"></span> <span>$LastFIORating</span><span class=\"pull-right\"><a href=detail.php?id=".$pid." type=\"button\" class=\"btn btn-default btn-xs\">�����������</a></span>
                              </h4>
                            </div>
                            <div id=\"collapse$pid\" class=\"panel-collapse collapse\" role=\"tabpanel\" aria-labelledby=\"heading$pid\">
                              <div class=\"panel-body\">
                                <table class=\"table table-hover\">
                                  <thead>
                                    <tr>
                                      <th>������������ �������</th>
                                      <th>������� ����</th>
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
                    $SumPrepod++; //��� �� ������ � ���� if, ������ �� ����������� �� ������ �������������
                }
                $DetailRecord=$DetailRecord."<tr>
                                              <td>$obj->question</td>
                                              <td>$obj->sum_answer</td>
                                              </tr>"; //��������� ������� � ������������
                //������ �� ���������� �������� � ������� ��� ������������ �������                                              
                $ValueAnswerForPrepod[$SumPrepod][$SumValueAnswerForPrepod][0]=$obj->question;
                $ValueAnswerForPrepod[$SumPrepod][$SumValueAnswerForPrepod][1]=$obj->sum_answer;
                $ValueAnswerForPrepod[$SumPrepod][$SumValueAnswerForPrepod][2]="$obj->fam $obj->imja $obj->otch";
                $SumValueAnswerForPrepod++; //������� ���������� ��������.
                $pid=$obj->id_prep;
                $LastFIO="$obj->fam $obj->imja $obj->otch";
                $LastFIORating=$obj->avv;
            }

            if($pid != 0) {
              //����� ���������� ����� ��� ��� ������� ��������� �������, �.�. �� ����� �� ��� ��������, � ��������� ������ �������� �� ������������.
                                echo "<div class=\"panel panel-default\">
                            <div class=\"panel-heading\" role=\"tab\" id=\"heading$pid\">
                              <h4 class=\"panel-title\">
                                <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse$pid\" aria-expanded=\"true\" aria-controls=\"collapse$pid\">
                                  $LastFIO</a><span> </span>
                                  <span class=\"glyphicon glyphicon-signal\" aria-hidden=\"true\"></span> <span>$LastFIORating</span><span class=\"pull-right\"><a href=detail.php?id=".$pid." type=\"button\" class=\"btn btn-default btn-xs\">�����������</a></span>
                              </h4>
                            </div>
                            <div id=\"collapse$pid\" class=\"panel-collapse collapse\" role=\"tabpanel\" aria-labelledby=\"heading$pid\">
                              <div class=\"panel-body\">
                                <table class=\"table table-hover\">
                                  <thead>
                                    <tr>
                                      <th>������������ �������</th>
                                      <th>������� ����</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    $DetailRecord
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>";
            } else { echo "<h3>������ �����������</h3>";}
          echo "</div>";
//////////////////////////////////////////////////////////////////          
//  ��������� JAVA ������, ������������� �� ��������� �������
//////////////////////////////////////////////////////////////////
echo "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>\n";
echo "<script type=\"text/javascript\">\n";
echo "  google.charts.load('current', {'packages':['line']});\n";
echo "  google.charts.setOnLoadCallback(drawChart);\n";
echo "  function drawChart() {\n";
echo "  var data = google.visualization.arrayToDataTable([\n";
        echo "['������'";
        for ($i=0; $i<$SumPrepod+1; $i++) {
          echo ", '".$ValueAnswerForPrepod[$i][0][2]."'";
        }
        echo "],";
          ///////////////////////////
          //��������� ������ ��������
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
          //������ �����������
          /////////////////////
//���������� ����������� JAVA ������
echo "  var options = {
         chart: {
           title: '������� ������ �� ��������������',
           subtitle: '� ������� �������� �����'
         },
         width: 1000,
         height: 500
         };
         var chart = new google.charts.Line(document.getElementById('linechart_material'));
         chart.draw(data, google.charts.Line.convertOptions(options));
       }
       </script>";
//��������� ����������� JAVA ������
///////////////////////////////////        
        ?>
         <div id="linechart_material"></div> <!-- � ���� ����� ������ ������ -->
    </div>
    <br>
<?php include('footer.php'); ?>