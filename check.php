        <?php
        // ������ ��������
        //echo "���� id: ".$_COOKIE['id'];
        //echo "<br>���� hash: ".$_COOKIE['hash'];
        if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
        {   
            $request = "SELECT UserHash, id FROM anstud_login WHERE id = '".$_COOKIE['id']."'";
            $res = sqlsrv_query($conn, $request);
            $obj = sqlsrv_fetch_object($res);

            //echo "<br>���  �� ��: ".$obj->UserHash;
            //echo "<br>id �� ��: ".$obj->id;
            //echo "<br>� ������ ����: id �� �� - ".gettype($obj->id)." id �� ���� - ".gettype($_COOKIE['id']);

            if(($obj->UserHash !== $_COOKIE['hash']) or ($obj->id !== intval($_COOKIE['id']))) 
            {   
                setcookie("id", "", time() - 3600*24*30*12, "/");
                setcookie("hash", "", time() - 3600*24*30*12, "/");
                print "<br>�������������� ��������. ����������, �������������� ������.";
                $Auth=0;
                header("Location: notauth.php"); exit();
            }
            else
            {
                //print "������! �� ��������!";
                $Auth=1;
            }
        }
        else
        {
            print "�������� ����, ����������.";
            $Auth=0;
        }
        ?>      