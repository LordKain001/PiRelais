<?php

include "TtyControl.php";

$comPort = new ttyControl("/dev/ttyUSB0");

while (true) {
	//sleep(2);
	echo $comPort->getData();	# code...

	//var_dump($comPort);
}

?>