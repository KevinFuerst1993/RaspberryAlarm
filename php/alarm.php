 <?php
// Enable error print for debug purposes
error_reporting(-1);
ini_set('display_errors','On');

$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("file", "/tmp/error-output.txt", "a") // stderr is a file to write to
);

//open log file
$logFile = fopen("/var/www/html/phpLog","a");
$timeStamp = date("Y-m-d H:i:s");
fwrite($logFile, "alarm.php executed at: $timeStamp\n");
   
$home="/home/pi/alarm/";
$con=new mysqli("localhost","alarm","alarm","alarm");
if(!$con)
{ 
	fwrite($logFile,"\tfailed to connect to database\n");
	fclose($logFile);
	die ("Database Connect failed");
}
$sql="select * from alarm where ts < now() order by ts asc";
$result = $con -> query($sql);
$row = $result->fetch_assoc();
if ($row["id"]) 
{
	fwrite($logFile, "\talarm time reached\n");
	fclose($logFile);
	//system('/usr/bin/nohup /usr/bin/ogg123 '.$home."sound/".$row["sound"].'>/dev/null  &' );
	//system('/usr/bin/nohup '.$home."light/".$row["light"].'>/dev/null  &' );
	//$handle = proc_open("/home/pi/Repos/RaspberryAlarm/light/".$row["light"].'>/dev/null  &' , $descriptorspec, $pipes);
	$handle = proc_open("/home/pi/Repos/RaspberryAlarm/light/sunrise", $descriptorspec, $pipes);
	exec('/usr/bin/gpio mode 0 in');
	$sql="delete from alarm where id=".$row["id"];
	$con -> query($sql);
	$sts=time();
	while(system('/usr/bin/gpio read 0',$o)) 
	{
		if(time()> ($sts+60)) 
		{
			$logFile = fopen("/var/www/html/phpLog","a");
			fwrite($logFile, "alarm timeout\n");
			break;
		}
	}
	if(system('/usr/bin/gpio read 0',$o))
	{
		fwrite($logFile, "button pressed\n");
	}
	
	//stop led proccess
	$status = proc_get_status($handle);
	$ppid = $status['pid'];
	//use ps to get all the children of this process, and kill them
	$pids = preg_split('/\s+/', `ps -o pid --no-heading --ppid $ppid`);
	foreach($pids as $pid) {
	  if(is_numeric($pid)) {
		  echo "Killing $pid\n";
		  posix_kill($pid, 9); //9 is the SIGKILL signal
	  }
	}
	proc_close($handle);
	
	system($home."light/all_off" );
}
system('/usr/bin/killall ogg123 ');
$con->close();
fclose($logFile);
 ?>
