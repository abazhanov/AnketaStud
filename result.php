<?php include('_header.php'); ?>
    <div class="container">
        <h1>������������� ������� ���������</h1>
        <?php
          //������ �����������
          //echo "�������� key: ".$_POST['id_user'];
          $FirstValue=1;
          foreach($_POST as $key => $value) {
                //echo "�������� key: ".$key;
                if($FirstValue==1) {
                  $FirstValue=0;
                  $id_user=$_POST['id_user'];
                }
                else {
                  $NV_Input=explode("&&&",$key);
                  echo "POST ������:".$NV_Input[0]." | id �������������: ".$NV_Input[1]." | �������� ������: ".$value." | ID ����������: ".$NV_Input[2]."<br>";
                  $question="INSERT anstud_main (id_prep, answer, date, question, id_disciplina) values ($NV_Input[1],$value,GETDATE(), $NV_Input[0], $NV_Input[2])";
                  $res_questions=sqlsrv_query($conn, $question);
                  if( $res_questions === false ) {
                    die( print_r( sqlsrv_errors(), true));
                  }
                }
          $request="UPDATE anstud_login SET Used=1 WHERE id=".$id_user;
          $res=sqlsrv_query($conn, $request);
          if( $res === false ) {
            die( print_r( sqlsrv_errors(), true));
          }
          }  
          echo "<h3>�������, ���� ������ ������!</h3>";
        ?>
    </div>
    <br>
<?php include('_footer.php'); ?>