<?php include('header.php'); ?>
    <div class="container">
        <h1>Преподаватель глазами студентов</h1>
        <?php
          //Проверяем атунтификацию пользователя
          $request="SELECT id, ForGroup, Used FROM anstud_login WHERE Login='".$_GET['login']."' and Password='".$_GET['password']."'";
          $res=sqlsrv_query($conn, $request);
          $obj = sqlsrv_fetch_object($res);
          if(isset($obj)) {
              $User=1;
              $id_user=$obj->id;
              if($obj->Used==1) {
                  echo "<h3>Вы уже участвовали в опросе.</h3>";
                  $User=0;
              }
          }
          else { echo "Такой логин или пароль не найден.";
              $User=0;
          }
          if($User!=0) { //Пользователь прошел аутентификацию и мы формируем для него список преподавателей и вопросов
            echo "<h2>Оцените Ваших преподавателей:</h2>";
            $request1="SELECT oid, kod FROM gruppa WHERE kod='".$obj->ForGroup."'";
            $res1=sqlsrv_query($conn, $request1);
            $obj = sqlsrv_fetch_object($res1);
            echo "<br><h3>Ваша группа: $obj->kod</h3>";
            //<!-- Get FIO prepod -->
            $request="SELECT DISTINCT prep_man.fam, prep_man.imja, prep_man.otch, prep_man.oid as pid, zplan.gruppa, prep_profile.oid, predmet.name, gruppa.kod, predmet.oid as oid_disciplina
                        FROM            zplan INNER JOIN pps ON zplan.pps = pps.oid 
                            INNER JOIN prep_profile ON pps.prep = prep_profile.oid 
                            INNER JOIN prep_man ON prep_profile.prep = prep_man.oid 
                            INNER JOIN predmet ON pps.predmet = predmet.oid 
                            INNER JOIN gruppa ON zplan.gruppa = gruppa.oid
                        WHERE        (zplan.gruppa = ".$obj->oid.")";
            //echo "<br>ТЕКСТ ЗАПРОСА: ".$request;
            $res=sqlsrv_query($conn, $request);
            //Получаю вопросы и их id
            $questions="SELECT        id, question
                        FROM            anstud_question";
            $res_questions=sqlsrv_query($conn, $questions);
            // <!-- Формируем таблицу преподавателей внутри формы -->
            echo "<form action=\"result.php\" method=\"post\">";
            echo "<input type=\"hidden\" name=\"id_user\" value=\"$id_user\">";
              while( $obj = sqlsrv_fetch_object($res)) { //Перебираем преподавателей
                echo "<h3>".$obj->fam." ".$obj->imja." ".$obj->otch." (".$obj->name.")</h3>";
                echo "<table class=\"table table-hover\">
                      <thead>
                        <tr>
                          <th>Критерий</th>
                          <th>Очень плохо</th>
                          <th>Плохо</th>
                          <th>Нормально</th>
                          <th>Хорошо</th>
                          <th>Очень хорошо</th>
                          <th>Я из другой подгруппы</th>
                      </tr>
                    </thead>
                    <tbody>";
                while( $obj_questions = sqlsrv_fetch_object($res_questions)) { //Перебираем вопросы
                          echo "<tr>
                        <td>".$obj_questions->question."</td> 
                          <td align=\"center\"><input type=\"radio\" name=\"$obj_questions->id&&&$obj->pid&&&$obj->oid_disciplina\" value=\"1\" required></td>
                          <td align=\"center\"><input type=\"radio\" name=\"$obj_questions->id&&&$obj->pid&&&$obj->oid_disciplina\" value=\"2\" required></td>
                          <td align=\"center\"><input type=\"radio\" name=\"$obj_questions->id&&&$obj->pid&&&$obj->oid_disciplina\" value=\"3\" required></td>
                          <td align=\"center\"><input type=\"radio\" name=\"$obj_questions->id&&&$obj->pid&&&$obj->oid_disciplina\" value=\"4\" required></td>
                          <td align=\"center\"><input type=\"radio\" name=\"$obj_questions->id&&&$obj->pid&&&$obj->oid_disciplina\" value=\"5\" required></td>
                          <td align=\"center\"><input type=\"radio\" name=\"$obj_questions->id&&&$obj->pid&&&$obj->oid_disciplina\" value=\"0\"></td>
                        </tr>";
                }
                echo"</tbody>
                    </table>";
                $res_questions=sqlsrv_query($conn, $questions);
              }
              echo "    <input type=\"submit\">
              </form>";
          }
        ?>
    </div>
    </div>
    <br>
<?php include('footer.php'); ?>