<?php include('header.php'); ?>
    <div class="container">
        <h1>Преподаватель глазами студентов</h1>
        <?php
        //Подключаем вторую БД, в которую будем писать
        $UID2="sa"; 
        $PWD2="Gthkjdrf"; 
        $serverName2 = "172.17.150.9"; 
        $connectionInfo2 = array( "Database"=>"HS", "UID"=>"$UID2", "PWD"=>"$PWD2"); 
        $conn2 = sqlsrv_connect( $serverName2, $connectionInfo2); 
        if(!$conn2) die(print_r (sqlsrv_errors(),true));    

        //Проба вставить строку
        //$request2="INSERT anstud_login (anstud_login.Login, anstud_login.Password, anstud_login.Date, anstud_login.ForGroup) values ('tlog', 'tpass', GETDATE(), 'FF123')";
        //$res_questions2=sqlsrv_query($conn2, $request2);

        $request="SELECT * FROM anstud_question";
        $res_question=sqlsrv_query($conn, $request);

        while( $obj = sqlsrv_fetch_object($res_question)) {
            echo $obj->question."<br>";

            $request2="INSERT anstud_question (anstud_question.question) values ('$obj->question')";
            $res_questions2=sqlsrv_query($conn2, $request2);
        }




        ?>
    </div>
    <br>
<?php include('footer.php'); ?>