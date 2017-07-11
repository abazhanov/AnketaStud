<?php include('header.php'); ?>
    <div class="container">
          <h1>Преподаватель глазами студентов</h1>
      <?php
        $chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP"; // Символы, которые будут использоваться в пароле. 
        $size=StrLen($chars)-1; // Определяем количество символов в $chars 
        echo "Количество пользователей: ".$_GET['NumberOfUser'];
        echo "<br>Название группы: ".$_GET['Gruppa'];
        echo "<table class=\"table\">
                    <thead>
                      <tr>
                        <th>Логин</th>
                        <th>Пароль</th>
                      </tr>
                    </thead>
                    <tbody>";
        for($i=1;$i<=$_GET['NumberOfUser'];$i++) {
            $max=10; // Количество символов в логине и пароле
            $login=null; // Определяем пустую переменную, в которую и будем записывать символы.
            while($max--) {
                $login.=$chars[rand(0,$size)]; 
            }
            $max=10; // Количество символов в логине и пароле
            $password=null; // Определяем пустую переменную, в которую и будем записывать символы. 
            // Создаём password 
            while($max--) {
                $password.=$chars[rand(0,$size)]; 
            }
            //Пишем строчку в БД и выводим ее на экран для печати
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