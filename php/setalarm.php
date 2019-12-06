<HTML>
    <form action="setalarm.php" method="post">
        <p>WakeupTime<br>YYYY-MM-DD HH:mm:ss<br><input type="text" name="ts"/></p>
	<p>Sound<br><input type="text" name="sound"/></p>
        <p>Light<br><input type="text" name="light"/></p>
	<p><input type="submit" name="save" value="Save" /></p>
    </form>

<?php
// Enable error print for debug purposes
error_reporting(-1);
ini_set('display_errors','On');

$con=new mysqli("localhost","alarm","alarm","alarm");

$logFile = fopen("/var/www/html/phpLog","a");
$timeStamp = date("Y-m-d H:i:s");
fwrite($logFile, "setalarm.php executed at: $timeStamp\n");

if(isset($_POST['save'])){
    $ts=$_POST['ts'];
    $sound=$_POST['sound'];
    $light=$_POST['light'];
    
    if(!$con)
	{
		fwrite($logFile,"\tfailed to connect to database\n");
		fclose($logFile);
		die ("Database Connect failed");
	}
    $sql="insert into alarm (ts,sound,light) values('".$ts."','".$sound."','".$light."')";
    if($con->query($sql)===false)
    {
		fwrite($logFile,"\tfailed to insert database entry\n");
		echo $con->error;
	}
	else
	{
		fwrite($logFile, "\tnew alarm added: $ts\n");
	}
}    
fclose($logFile);

$sql="select * from alarm order by ts asc";
$result = $con->query($sql);
echo "<table border=1>";
while($row = $result->fetch_assoc()){
    echo "<tr><td>".$row["ts"]."</td><td>".$row["sound"]."</td><td>".$row["light"]."</td></tr>";
}
echo "</table>";
$con->close();
?>

</HTML>
