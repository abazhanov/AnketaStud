<?php include 'header.php'; ?>
    <div class="container">
          <h1>Преподаватель глазами студентов</h1>
          <p>Уважаемые студенты, доступ к анкетированию осуществляется по логинам, выданным деканатом.</p>
      <?php
        $request="SELECT oid, kod FROM gruppa WHERE sub<>1 ORDER BY kod";
        $res=sqlsrv_query($conn, $request);
      ?>
      <form action="rating.php" method="get">
        <p>Введите логин: <input type="text" name="login"></p>
        <p>Введите пароль: <input type="password" name="password"></p>
        <p><input type="submit" value="Войти"></p>
      </form>
    </div>
<?php include 'footer.php';?>