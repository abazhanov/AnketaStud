<?php include('header.php'); ?>
    <div class="container">
        <h1>������������� ������� ���������</h1>


<?php
// �������� ����������� ������ ������������
# ���������� � ��


if(isset($_POST['submit'])) {
    $err = array();

    # �������� �����
    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
    {
        $err[] = "����� ����� �������� ������ �� ���� ����������� �������� � ����";
    }

    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
    {
        $err[] = "����� ������ ���� �� ������ 3-� �������� � �� ������ 30";
    }

    # ���������, �� ����������-�� ������������ � ����� ������
    $request = "SELECT COUNT(id) FROM anstud_login WHERE Login='".$_POST['login']."'";
    $res=sqlsrv_query($conn, $request);

    //if(mysql_result($query, 0) > 0)
    //{
    echo "RES:".$res."<br>";
    $obj = sqlsrv_fetch_object($res);
    echo "id: ".$obj->id;
    if($obj->id)
    {
        $err[] = "������������ � ����� ������� ��� ���������� � ���� ������";
    }
    //}

    # ���� ��� ������, �� ��������� � �� ������ ������������
    if(count($err) == 0)
    {
        $login = $_POST['login'];

        # ������� ������ ������� � ������ ������� ����������
        $password = md5(md5(trim($_POST['password'])));

        $request2 = "INSERT INTO anstud_login SET Login='".$login."', Password='".$password."'";
        $request2= "INSERT anstud_login (anstud_login.Login, anstud_login.Password, anstud_login.Date, anstud_login.ForGroup, anstud_login.NotStudent) values ('$login', '$password', GETDATE(), 'KAFEDRA', 1)";
        $res2=sqlsrv_query($conn, $request2);

        header("Location: login.php"); exit();
    }
    else
    {
        print "<b>��� ����������� ��������� ��������� ������:</b><br>";

        foreach($err AS $error)
        {
            print $error."<br>";
        }
    }
}
?>

<form method="POST">
����� <input name="login" type="text"><br>
������ <input name="password" type="password"><br>
<input name="submit" type="submit" value="������������������">
</form>

</div>
<?php include('footer.php'); ?>
