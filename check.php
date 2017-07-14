        <?php
        // Скрипт проверки
        //echo "Куки id: ".$_COOKIE['id'];
        //echo "<br>Куки hash: ".$_COOKIE['hash'];
        if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
        {   
            $request = "SELECT UserHash, id FROM anstud_login WHERE id = '".$_COOKIE['id']."'";
            $res = sqlsrv_query($conn, $request);
            $obj = sqlsrv_fetch_object($res);

            //echo "<br>Хэш  из БД: ".$obj->UserHash;
            //echo "<br>id из БД: ".$obj->id;
            //echo "<br>А теперь типы: id из БД - ".gettype($obj->id)." id из хэша - ".gettype($_COOKIE['id']);

            if(($obj->UserHash !== $_COOKIE['hash']) or ($obj->id !== intval($_COOKIE['id']))) 
            {   
                setcookie("id", "", time() - 3600*24*30*12, "/");
                setcookie("hash", "", time() - 3600*24*30*12, "/");
                print "<br>Аутентификация неудачна. Пожалуйста, авторизуейтесь заново.";
                $Auth=0;
                header("Location: notauth.php"); exit();
            }
            else
            {
                //print "Привет! Всё работает!";
                $Auth=1;
            }
        }
        else
        {
            print "Включите куки, пожалуйста.";
            $Auth=0;
        }
        ?>      