<HTML>
  <form action="setalarm.php" method="post">
   <p>Alarm time<br>YYYY-MM-DD HH:mm:ss<br><input type="text" name="ts"/></p>
   <p>Sound<br><input type="text" name="sound"/></p>
   <p>Light<br><input type="text" name="light"/></p>
   <p><input type="submit" name="save" value="store" /></p>
  </form>
  <?php
   $con=new mysqli("localhost","alarm","alarm","alarm");
   if(isset($_POST['save'])) {
     $ts=$_POST['ts'];
     $sound=$_POST['sound'];
     $light=$_POST['light'];
     if(!$con){ die ("Database Connect failed"); }
     $sql="insert into alarm (ts,sound,light) values('".$ts."','".$sound."','".$light."')";
     if($con->query($sql)===false){echo $con->error;}
   }
    $sql="select * from alarm order by ts asc";
    $result = $con->query($sql);
    echo "<table border=1>";
    while($row = $result->fetch_assoc()) {
      echo "<tr><td>".$row["ts"]."</td><td>".$row["sound"]."</td><td>".$row["light"]."</td></tr>";
    }
    echo "</table>";
    $con->close();
  ?>
</HTML>
