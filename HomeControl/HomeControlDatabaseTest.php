<?php

include 'HomeControlDatabase.php';
include "../AdamControl/AdamControl.php";

$adam = new adamControl("/dev/ttyUSB0");

$adam->findAdams(1,5);
var_dump($adam);
	
	echo $adam->getStatus(0);	# code...
	

$db = new homeControlDatabase();

$db->updateAdams($adam->reportAdams(1));

while(1)
{
	for ($i=0; $i < 4; $i++) { 
		echo $adam->controlOutput(1,$i,1);
		
		sleep(1);
		
		

		for ($k=0; $k < 10; $k++) 
		{ 
			$eventadams["new"] = $adam->reportAdams(0,"4060");
			if ($eventadams["old"] != $eventadams["new"]) 
			{
				echo "new Data \r\n";
				$eventadams["old"] = $eventadams["new"];

				foreach ($eventadams["old"] as $key => $value)
				{
					$db->createEvent("DataIn",$value);
				}

			}else
			{
				echo "no New Data \r\n";
			}
		}

		
		
		echo $adam->controlOutput(1,$i,0);
		
		
	}
}




?>