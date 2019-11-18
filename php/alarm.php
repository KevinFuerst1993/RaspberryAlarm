 <?php
   $home="/home/pi/alarm/";
   //system($home."light/all_off" ); system($home."light/all_off");
   //a comment
   $con=new mysqli("localhost","alarm","alarm","alarm");
   if(!$con){ die ("Database Connect failed"); }
   $sql="select * from alarm where ts < now() order by ts asc";
   $result = $con -> query($sql);
   $row = $result->fetch_assoc();
   if ($row["id"]) {
     system('/usr/bin/nohup /usr/bin/ogg123 '.$home."sound/".$row["sound"].'>/dev/null  &' );
     system('/usr/bin/nohup '.$home."light/".$row["light"].'>/dev/null  &' );
     exec('/usr/local/bin/gpio mode 0 in');
     $sql="delete from alarm where id=".$row["id"];
     $con -> query($sql);
     $sts=time();
     while(!system('/usr/local/bin/gpio read 0',$o)) {
       if(time()> ($sts+600)) {
         break;
       }
     }
   }
 system('/usr/bin/killall ogg123 ');
 $con->close();
 ?>
