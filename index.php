<?php include 'header.php'; ?>
    <div class="container">
          <h1>������������� ������� ���������</h1>
          <p>��������� ��������, ������ � ������������� �������������� �� �������, �������� ���������.</p>
      <?php
        $request="SELECT oid, kod FROM gruppa WHERE sub<>1 ORDER BY kod";
        $res=sqlsrv_query($conn, $request);
      ?>
      <form action="rating.php" method="get">
        <p>������� �����: <input type="text" name="login"></p>
        <p>������� ������: <input type="password" name="password"></p>
        <p><input type="submit" value="�����"></p>
      </form>
    </div>
<?php include 'footer.php';?>