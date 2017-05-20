<?php include('_header.php'); ?>
    <div class="container">
        <h1>������������� ������� ���������</h1>
        <?php
          //���������� � ����� ������������� ������������� � ������� ���������
          //echo "������ id: ".$_GET['id'];
          $request="SELECT prep_man.fam, prep_man.imja, prep_man.otch, predmet.name, predmet.oid as id_disciplina, id_prep
                    FROM anstud_main
	                        INNER JOIN prep_man ON id_prep=prep_man.oid
	                        INNER JOIN predmet ON id_disciplina = predmet.oid
                    WHERE prep_man.oid=".$_GET['id']."
                    GROUP BY prep_man.fam, prep_man.imja, prep_man.otch, predmet.name, predmet.oid, id_prep";
          $res=sqlsrv_query($conn, $request);
          $PrintFIO=0;
          echo "<div class=\"panel-group\" id=\"accordion\" role=\"tablist\" aria-multiselectable=\"true\">";
          $Hat="['������'"; //��������� ������ 1-�� ������ ������� ��� ������� 
          $NQuestion=1; //������������� �������� ��� 1-�� ������� �������
          $NDisciplina=1; //������������� �������� ��� 2-�� ������� �������
          while( $obj = sqlsrv_fetch_object($res)) {
            $Hat=$Hat.", '".$obj->name."'"; //����������� � 1-�� ������ ������� ��������� �������� ����������
            if($PrintFIO==0) { //���� �� ��� �� ������ ��� �������������, �� ������� ���
              echo "<h2>$obj->fam $obj->imja $obj->otch</h2>";
              $PrintFIO=1;
            }
            $pid=$obj->id_disciplina;
            echo "<div class=\"panel panel-default\"> 
                    <div class=\"panel-heading\" role=\"tab\" id=\"heading$pid\">
                      <h4 class=\"panel-title\">
                        <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse$pid\" aria-expanded=\"true\" aria-controls=\"collapse$pid\">$obj->name</a>
                      </h4>
                    </div>
                  <div id=\"collapse$pid\" class=\"panel-collapse collapse\" role=\"tabpanel\" aria-labelledby=\"heading$pid\">
                  <div class=\"panel-body\">";
            echo "<table class=\"table table-hover\">
                  <thead>
                    <tr>
                      <th>������������ �������</th>
                      <th>������� ����</th>
                    </tr>
                  </thead>
                  <tbody>";            
            $request2="SELECT anstud_question.question, round(AVG(anstud_main.answer),2) as avv
                    FROM anstud_main
                    INNER JOIN anstud_question ON anstud_main.question = anstud_question.id
                    INNER JOIN predmet ON id_disciplina = predmet.oid
                    WHERE id_prep=$obj->id_prep and id_disciplina=$obj->id_disciplina
                    GROUP BY anstud_question.question";
            $res2=sqlsrv_query($conn, $request2);
            $NQuestion=1;
            while( $obj2 = sqlsrv_fetch_object($res2)) {
              echo "    <tr>
                          <td>$obj2->question</td>
                          <td>$obj2->avv</td>
                        </tr>"; //��������� ������� � ������������
              $MasQuestion[$NQuestion][0]=$obj2->question; //����� ��� ������������ �������� �������
              $MasQuestion[$NQuestion][$NDisciplina]=$obj2->avv; //����� ������� ����
              $NQuestion++;
            }
            $NDisciplina++;
            echo "   </tbody>
                  </table>";
            echo "</div>
                </div>
              </div>";      
          }
          $Hat=$Hat."],"; //����� ������������ 1-�� ������ �������
          //����� ������ �� ������� � ���� ������
          for($i=1; $i<$NQuestion-1; $i++) {
            $Hat=$Hat."[";
            $FirstValue=1;
            for($j=0; $j<$NDisciplina; $j++) {
              if($FirstValue==0) {
                $Hat=$Hat.",".$MasQuestion[$i][$j];
              } else {
                $Hat=$Hat."'".$MasQuestion[$i][$j]."'";
                $FirstValue=0;
                }
            }
            $Hat=$Hat."],";
          }
          $Hat=$Hat."[";
          $FirstValue=1;
          for($j=0; $j<$NDisciplina; $j++) {
            if($FirstValue==0) {
              $Hat=$Hat.",".$MasQuestion[$i][$j];
              } else {
                $Hat=$Hat."'".$MasQuestion[$i][$j]."'";
                $FirstValue=0;
              }
          }
          $Hat=$Hat."]";
          ?>
<?php
//////////////////////////////////////////////////////////////////          
//  ��������� JAVA ������, ������������� �� ��������� �������
//////////////////////////////////////////////////////////////////
echo "<script type=\"text/javascript\">";
echo "  google.charts.load('current', {'packages':['line']});";
echo "  google.charts.setOnLoadCallback(drawChart);";
echo "  function drawChart() {";
echo "  var data = google.visualization.arrayToDataTable([";
      ///////////////////////////
      //��������� ������ ��������
echo $Hat;
      //������ �����������
      /////////////////////
echo "  ]);";
echo "  var options = {
         chart: {
           title: '�������� �������� �����',
           subtitle: '�� �����������'
         },
         width: 1000,
         height: 500
         };

         var chart = new google.charts.Line(document.getElementById('linechart_material'));
         chart.draw(data, google.charts.Line.convertOptions(options));

       }
       </script>";
    ?>
    <br>
    <div id="linechart_material"></div> <!-- � ���� ����� ������ ������ -->
<?php include('_footer.php'); ?>