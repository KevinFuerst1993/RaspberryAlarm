 <?php
// Enable error print for debug purposes
error_reporting(-1);
ini_set('display_errors','On');

//open log file
$logFile = fopen("/var/www/html/phpLog","a");
$timeStamp = date("Y-m-d H:i:s");
fwrite($logFile, "setAlarmOverWidget.php executed at: $timeStamp\n");

//get the passed arguments
$alarmDate = $_GET['argument1'];
$alarmTime = $_GET['argument2'];
fwrite($logFile,"\talarm date: $alarmDate\n");
fwrite($logFile,"\talarm time: $alarmTime\n");
$alarmValue = "$alarmDate $alarmTime:00";

$con=new mysqli("localhost","alarm","alarm","alarm");
if(!$con)
{
	fwrite($logFile,"\tfailed to connect to database\n");
	fclose($logFile);
	die ("Database Connect failed");
}

//set default sound and light
$sound = "";
$light = "sunrise";

//make database entry
$sql="insert into alarm (ts,sound,light) values('".$alarmValue."','".$sound."','".$light."')";
if($con->query($sql)===false)
{
	fwrite($logFile,"\tfailed to insert database entry\n");
	echo $con->error;
}
else
{
	fwrite($logFile,"\tnew alarm added: $alarmValue\n");
} 

fclose($logFile);
 ?>
