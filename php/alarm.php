 <?php
// Enable error print for debug purposes
error_reporting(-1);
ini_set('display_errors','On');

//open log file
$logFile = fopen("/var/www/html/phpLog","a");

fwrite($logFile, "alarm.php executed at: ");
$timeStamp = date("Y-m-d H:i:s");
fwrite($logFile, $timeStamp);
fwrite($logFile,"\n");
   
$home="/home/pi/alarm/";
$con=new mysqli("localhost","alarm","alarm","alarm");
if(!$con)
{ 
	fwrite($logFile, "Database Conncet failed");
	die ("Database Connect failed");
}
$sql="select * from alarm where ts < now() order by ts asc";
$result = $con -> query($sql);
$row = $result->fetch_assoc();
if ($row["id"]) 
{
	fwrite($logFile, "alarm time reached\n");
	fclose($logFile);
	system('/usr/bin/nohup /usr/bin/ogg123 '.$home."sound/".$row["sound"].'>/dev/null  &' );
	system('/usr/bin/nohup '.$home."light/".$row["light"].'>/dev/null  &' );
	exec('/usr/local/bin/gpio mode 0 in');
	$sql="delete from alarm where id=".$row["id"];
	$con -> query($sql);
	$sts=time();
	while(!system('/usr/local/bin/gpio read 0',$o)) 
	{
		if(time()> ($sts+60)) 
		{
			$logFile = fopen("/var/www/html/phpLog","a");
			fwrite($logFile, "alarm timeout\n");
			break;
		}
	}
	system($home."light/all_off" );
}
system('/usr/bin/killall ogg123 ');
$con->close();
fclose($logFile);
 ?>
