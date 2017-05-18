<html>
  <head>
      <meta charset="windows-1251">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Преподаватель глазами студентов</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dopstyle.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
          <h1>Преподаватель глазами студентов</h1>
      <?php
        header("Content-Type: text/html; charset=cp1251");
        $UID="viewer"; 
        $PWD="qaz123"; 
        $serverName = "172.17.3.7"; 
        $connectionInfo = array( "Database"=>"HS", "UID"=>"$UID", "PWD"=>"$PWD"); 
        $conn = sqlsrv_connect( $serverName, $connectionInfo); 
        if(!$conn) die(print_r (sqlsrv_errors(),true));    

        //Сначала получим список всех subgroup из таблицы gcross
        $request = "SELECT gruppa, subgruppa from gcross where gruppa=13097";
        $res=sqlsrv_query($conn, $request);
        echo "<form action=\"_selectsubfruppa.php\">";
        $ArrSubGruppa="(";
        while( $obj = sqlsrv_fetch_object($res)) { //Перебираем все id subgruppa 
          //echo "<h3>Группа: $obj->gruppa | Имя подгруппы: $obj->subgruppa</h3>"; //Выводим ID Группы и имя subgruppa
            if($ArrSubGruppa=="(") {
              $ArrSubGruppa=$ArrSubGruppa.$obj->subgruppa;
            } else {
              $ArrSubGruppa=$ArrSubGruppa.", ".$obj->subgruppa;
            }
        }
        $ArrSubGruppa=$ArrSubGruppa.")";
          //Создаем запрос для извлечения ФИО преподавателя, предмета и имя подгруппы
          $request_predmeti= "SELECT DISTINCT prep_man.fam, prep_man.imja, prep_man.otch, prep_man.oid as pid, zplan.gruppa, prep_profile.oid, predmet.name, gruppa.kod, predmet.oid as oid_disciplina
                        FROM            zplan INNER JOIN pps ON zplan.pps = pps.oid 
                           INNER JOIN prep_profile ON pps.prep = prep_profile.oid 
                            INNER JOIN prep_man ON prep_profile.prep = prep_man.oid 
                            INNER JOIN predmet ON pps.predmet = predmet.oid 
                            INNER JOIN gruppa ON zplan.gruppa = gruppa.oid
                        WHERE        zplan.gruppa IN ".$ArrSubGruppa;
          echo "ТЕКСТ ЗАПРОСА: ".$request_predmeti;
          $res_predmeti=sqlsrv_query($conn, $request_predmeti);
          while( $obj_predmeti = sqlsrv_fetch_object($res_predmeti)) {  
            //echo "<br>$obj_predmeti->fam $obj_predmeti->imja $obj_predmeti->otch $obj_predmeti->name $obj_predmeti->kod"; //Выводим ФИО преподавателя, предмета и имя подгруппы
            echo "<br><input type=\"checkbox\" checked=\"checked\"/> $obj_predmeti->fam $obj_predmeti->imja $obj_predmeti->otch <strong>$obj_predmeti->name</strong> $obj_predmeti->kod";

          }
        
        echo "</form>";
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