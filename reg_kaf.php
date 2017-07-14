<?php include('header.php'); ?>
    <div class="container">
        <h1>Преподаватель глазами студентов</h1>


<?php
// Страница регситрации нового пользователя
# Соединямся с БД


if(isset($_POST['submit'])) {
    $err = array();

    # проверям логин
    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
    {
        $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    }

    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
    {
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    # проверяем, не существует-ли пользователя с таким именем
    $request = "SELECT COUNT(id) FROM anstud_login WHERE Login='".$_POST['login']."'";
    $res=sqlsrv_query($conn, $request);

    //if(mysql_result($query, 0) > 0)
    //{
    echo "RES:".$res."<br>";
    $obj = sqlsrv_fetch_object($res);
    echo "id: ".$obj->id;
    if($obj->id)
    {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }
    //}

    # Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {
        $login = $_POST['login'];

        # Убераем лишние пробелы и делаем двойное шифрование
        $password = md5(md5(trim($_POST['password'])));

        $request2 = "INSERT INTO anstud_login SET Login='".$login."', Password='".$password."'";
        $request2= "INSERT anstud_login (anstud_login.Login, anstud_login.Password, anstud_login.Date, anstud_login.ForGroup, anstud_login.NotStudent) values ('$login', '$password', GETDATE(), 'KAFEDRA', 1)";
        $res2=sqlsrv_query($conn, $request2);

        header("Location: login.php"); exit();
    }
    else
    {
        print "<b>При регистрации произошли следующие ошибки:</b><br>";

        foreach($err AS $error)
        {
            print $error."<br>";
        }
    }
}
?>

<form method="POST">
Логин <input name="login" type="text"><br>
Пароль <input name="password" type="password"><br>
<input name="submit" type="submit" value="Зарегистрироваться">
</form>

</div>
<?php include('footer.php'); ?>
