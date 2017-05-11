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
      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
          /////////////////////////////////
          //������� ����� �� ��������������
          $request="SELECT     anstud_main.id_prep, prep_man.fam, prep_man.imja, prep_man.otch, anstud_question.question, round(AVG(anstud_main.answer), 2) sum_answer, round(AVG(man.avgg ),2) as avv  
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

 
                         
          echo "</div>";
//////////////////////////////////////////////////////////////////          
//  ��������� JAVA ������, ������������� �� ��������� �������
//////////////////////////////////////////////////////////////////
 
echo "<script type=\"text/javascript\">";
//echo "  google.charts.load('current', {'packages':['line']});";
echo "  google.charts.load('current', {'packages':['line']});";
echo "  google.charts.setOnLoadCallback(drawChart);";
echo "  function drawChart() {";
echo "  var data = google.visualization.arrayToDataTable([";
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
         <div id="linechart_material_1"></div> <!-- � ���� ����� ������ ������ -->
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