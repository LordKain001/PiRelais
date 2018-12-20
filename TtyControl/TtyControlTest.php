<?php

include "TtyControl.php";

$comPort = new ttyControl("/dev/ttyUSB0");
$adress = 1;
$data = sprintf("$%02X6\r",$adress);

while (true) {
	//sleep(2);
	
	$comPort->writeData($data);
	usleep(10000);
	echo $comPort->getData();	
	//usleep(10000);
	
	
	
	

	//var_dump($comPort);
}

?>