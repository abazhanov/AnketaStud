<?php include('_header.php'); ?>
    <div class="container">
          <h1>Преподаватель глазами студентов</h1>
      <?php      
          $request="SELECT oid, kod FROM gruppa WHERE sub<>1 ORDER BY kod"; //Текст запроса
          $res=sqlsrv_query($conn, $request); //Выполнение запроса
      ?>
      <div><!-- Форма для запроса количества создаваемых логинов и для какой группы -->
        <h3>Создание логинов и паролей для пользователей</h3>
        <form action="generate.php" method="get">
          <p>Введите количество создаваемых пользователей (логины):<br><input type="number" name="NumberOfUser"></p>
          <p><select size="10" multiple name="Gruppa">
            <?php
              while( $obj = sqlsrv_fetch_object($res)) {
                echo "<option value=\"$obj->kod\">$obj->kod</option>";
              }
            ?>
          </select></p>
          <p><input type="submit" value="Создать"></p>
        </form>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
      $(function () {
        $('[data-toggle="popover"]').popover()
      })
    </script>
<?php include('_footer.php'); ?>