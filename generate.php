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
  </head>
  <body>
  
    <div class="container">
          <h1>������������� ������� ���������</h1>

      <?php //���� ����������� � ��
        header("Content-Type: text/html; charset=cp1251");
        $UID="viewer"; 
        $PWD="qaz123"; 
        $serverName = "172.17.3.7"; 
        $connectionInfo = array( "Database"=>"HS", "UID"=>"$UID", "PWD"=>"$PWD"); 
        $conn = sqlsrv_connect( $serverName, $connectionInfo); 
        if(!$conn) die(print_r (sqlsrv_errors(),true));    
        //$request="SELECT oid, kod FROM gruppa WHERE sub<>1 ORDER BY kod"; //����� �������
        //$res=sqlsrv_query($conn, $request); //���������� �������


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
                //<p>Login: $login | Password: $password</p>";
            }
        }
        echo "  </tbody>
              </table>";
?>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
      $(function () {
        $('[data-toggle="popover"]').popover()
      })
    </script>


    
  </body>
</html>