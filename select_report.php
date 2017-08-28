<?php include('header.php'); ?>
    <div class="container">
        <h1>������������� ������� ���������</h1>
        <h2>�������� ������������ ������� ��� �������� ������ ���� ��������������</h2>
        <?php
          include('check.php'); //�������� �������������.
          if($Auth==0) { header("Location: notauth.php"); exit();  }

          ////////////////////////////////////
          // ������� ������ ������ ��� ������
          $request_kaf = "SELECT oid, name FROM kafedry WHERE podrazdelenie = 241";
          $res_kaf=sqlsrv_query($conn, $request_kaf);

          echo "<div class=\"list-group\">";
          echo "<a href=report.php?kaf=0 class=\"list-group-item active\">��� �������������</a>";
          while( $obj = sqlsrv_fetch_object($res_kaf)) {
            //����� ������ �� ������� ������ �������. ��, � ���� ��� ��� �� ��������� ��� ������ :)
            $request_kaf_rating = " SELECT round(AVG(man.avgg ),2) as avv  
                                    FROM anstud_main 
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
                                    WHERE kafedry.oid=$obj->oid";
            $res_kaf_rating=sqlsrv_query($conn, $request_kaf_rating);
            $obj_kaf_rating = sqlsrv_fetch_object($res_kaf_rating);
            echo "<a href=report.php?kaf=$obj->oid class=\"list-group-item\">$obj->name <span class=\"glyphicon glyphicon-signal\" aria-hidden=\"true\"></span> - $obj_kaf_rating->avv</a>";
          }
          echo "</div>";

          echo "<br><h2>������� ����������, ��� ����������� ���� ���</h2>";

          $request_discip = " SELECT predmet.longname, round(AVG(answer),2) as rating FROM anstud_main
                              INNER JOIN predmet ON anstud_main.id_disciplina = predmet.oid
                              GROUP BY predmet.longname
                              ORDER BY rating desc";
          $res_discip=sqlsrv_query($conn, $request_discip);
          
          echo "<table class=\"table table-hover\">
                  <thead>
                    <tr>
                      <th>����������</th>
                      <th>�������</th>
                    </tr>
                  </thead>
                  <tbody>";




          while( $obj_discip = sqlsrv_fetch_object($res_discip)) {
            echo "<tr>";
            echo "  <td>$obj_discip->longname</td>";
            echo "  <td>$obj_discip->rating</td>";
            echo "</tr>";
            
          }
          echo "</tbody>";
          echo "</table>";
          
          

         include('footer.php'); ?>