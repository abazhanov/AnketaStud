<?php include('header.php'); ?>
    <div class="container">
          <h1>������������� ������� ���������</h1>
      <?php
        $chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP"; // �������, ������� ����� �������������� � ������. 
        $size=StrLen($chars)-1; // ���������� ���������� �������� � $chars 
        echo "���������� �������������: ".$_GET['NumberOfUser'];
        echo "<br>�������� ������: ".$_GET['Gruppa'];
        echo "<table class=\"table\">
                    <thead>
                      <tr>
                        <th>�����</th>
                        <th>������</th>
                      </tr>
                    </thead>
                    <tbody>";
        for($i=1;$i<=$_GET['NumberOfUser'];$i++) {
            $max=10; // ���������� �������� � ������ � ������
            $login=null; // ���������� ������ ����������, � ������� � ����� ���������� �������.
            while($max--) {
                $login.=$chars[rand(0,$size)]; 
            }
            $max=10; // ���������� �������� � ������ � ������
            $password=null; // ���������� ������ ����������, � ������� � ����� ���������� �������. 
            // ������ password 
            while($max--) {
                $password.=$chars[rand(0,$size)]; 
            }
            //����� ������� � �� � ������� �� �� ����� ��� ������
            $request="INSERT anstud_login (anstud_login.Login, anstud_login.Password, anstud_login.Date, anstud_login.ForGroup) values ('$login', '$password', GETDATE(), '".$_GET['Gruppa']."')";
            $res_questions=sqlsrv_query($conn, $request);
            if( $res_questions === false ) {
                die( print_r( sqlsrv_errors(), true));
            } else {
                echo "<tr>
                        <td>$login</td>
                        <td>$password</td>
                      </tr>";
            }
        }
        echo "  </tbody>
              </table>";
?>
    </div>
<?php include('footer.php'); ?>