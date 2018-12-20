<?php

include "AdamControl.php";

$adam = new adamControl("/dev/ttyUSB0");

$adam->findAdams(1,10);
$adam->reportAdams(1);

var_dump($adam);
	
	echo $adam->getStatus(0);	# code...
	$adam->reportAdams(1);

	$adam->reportAdams(1,"4060");
	
	
	for ($i=0; $i < 4; $i++) { 
		echo $adam->controlOutput(1,$i,1);
		echo $adam->getStatus(0);
		sleep(1);
		echo $adam->controlOutput(1,$i,0);
		echo $adam->getStatus(0);
	}

	for ($i=0; $i < 4; $i++) { 
		echo $adam->controlOutput(1,$i,1);
		echo $adam->getStatus(0);
		sleep(1);
		
	}

	for ($i=0; $i < 4; $i++) { 
		sleep(1);
		echo $adam->controlOutput(1,$i,0);
		echo $adam->getStatus(0);
	}






?>