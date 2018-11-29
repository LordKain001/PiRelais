<?php

include "AdamControl.php";

$adam = new adamControl("/dev/ttyUSB0");

$adam->findAdams(1,5);
$adam->reportAdams(1);

while (true) {
	sleep(2);
	echo $adam->getStatus(0);	# code...
	$adam->reportAdams(1);
	
	for ($i=0; $i < 4; $i++) { 
		echo $adam->controlOutput(1,$i,1);
		sleep(1);
	}
	for ($i=0; $i < 4; $i++) { 
		echo $adam->controlOutput(1,$i,0);
		sleep(1);
	}	
}



?>