<?php

include 'HomeControlDatabase.php';
include "../AdamControl.php";

$adam = new adamControl("/dev/ttyUSB0");

$adam->findAdams(1,5);
var_dump($adam);
	
	echo $adam->getStatus(0);	# code...
	

$db = new homeControlDatabase();


while(1)
{
	for ($i=0; $i < 4; $i++) { 
		echo $adam->controlOutput(1,$i,1);
		$db->updateAdmas($adam->reportAdams(1));
		sleep(1);
		echo $adam->controlOutput(1,$i,0);
		$db->updateAdmas($adam->reportAdams(1));
	}
}




?>