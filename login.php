<?php include('header.php'); ?>
    <div class="container">
        <h1>Преподаватель глазами студентов</h1>
<?php
// Страница авторизации
//Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];  
    }
    return $code;
}

//echo "POST:".$_POST['submit']."<br>";
//echo "Login: ".$_POST['login']."<br>";
//echo "Password: ".$_POST['password']."<br>";

if(isset($_POST['submit']))
{
    // Вытаскиваем из БД запись, у которой логин равняется введенному
    $request = "SELECT id, Password FROM anstud_login WHERE Login='".$_POST['login']."'";
    $res=sqlsrv_query($conn, $request);
    $obj = sqlsrv_fetch_object($res);
    
    if($obj) { //Проверяем, есть ли результат у запроса. Т.е. есть ли в БД вообще такой логин
        // Сравниваем пароли, раз такой логин есть
        //echo "<br>Хэш пароля из БД:".$obj->Password."<br>";

        if($obj->Password === md5(md5($_POST['password']))) 
        {
            // Генерируем случайное число и шифруем его
            $hash = md5(generateCode(10));

            //Записываем в БД новый хеш авторизации
            $request2 = "UPDATE anstud_login SET UserHash='$hash' WHERE id='".$obj->id."'";
            $res2 = sqlsrv_query($conn, $request2);
            //Ставим куки
            setcookie("id", $obj->id, time()+60*60*24*30);
            setcookie("hash", $hash, time()+60*60*24*30);

            //Переадресовываем браузер на страницу проверки нашего скрипта
            header("Location: report.php"); exit();
        }
        else {
            print "<h3>Вы ввели неправильный логин/пароль</h3>";
        }
    }
    else 
    {
        print "<h3>Вы ввели неправильный логин/пароль</h3>";
    }
}
?>

<br><br>

<form method="POST" class="form-horizontal">

<div class="form-group">
    <label for="login" class="col-xs-2 control-label">Логин:</label>
    <div class="col-xs-10">
      <input type="login" class="form-control" id="login" name="login" placeholder="Введите логин">
    </div>
  </div>
<div class="form-group">
    <label for="password" class="col-xs-2 control-label">Пароль:</label>
    <div class="col-xs-10">
      <input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль">
    </div>
  </div>
  <div class="form-group">
    <div class="col-xs-offset-2 col-xs-10">
      <button type="submit" name="submit" class="btn btn-default">Войти</button>
    </div>
  </div>

</form>

</div>
<?php include('footer.php'); ?>