<?php

$configFile = "config.json";
$config = NULL;


if (file_exists($configFile))
{
	$config = json_decode(file_get_contents($configFile), TRUE);	
}else
{
	$config = array(
		"installStatus" => 1,
	);
}


	//check for Reset
	//If it cant pass the whole script the Error count will increase(2 times wirte on file)
	$statusFile = './MinerAlive/status.json';
	unlink($statusFile);


switch ($config["installStatus"]) {
	case '1':		
			passthru("sudo cp -v HomeControl.service /etc/systemd/system");		
			passthru("sudo cp -v HomeControl.service /etc/systemd/system/multi-user.target.wants");		
		break;
	
	default:
		# code...
		break;
}


if ($config["installStatus"]<2) {
	
	$config["installStatus"]++;
	var_dump($config);
	file_put_contents($configFile, json_encode($config));
}



		

include "../AdamControl/AdamControl.php";
include "./HomeControlDatabase.php";


$adam = new adamControl("/dev/ttyUSB0");

$adam->findAdams(1,5);
var_dump($adam);
	
echo $adam->getStatus(0);	# code...
$eventadams["old"] = $adam->reportAdams(0,"4053");	

$db = new homeControlDatabase(); 


while(1)
{
	$eventadams["new"] = $adam->reportAdams(0,"4053");
	if ($eventadams["old"] != $eventadams["new"]) 
	{
		echo "create Event\r\n";
		$eventadams["old"] = $eventadams["new"];

		foreach ($eventadams["old"] as $key => $value)
		{
			$db->createEvent("DataIn",$value);
		}

	}



	$switchCommands = $db->GetSwitchCommand();
	if (!empty($switchCommands)) {
		foreach ($switchCommands as $key => $value) 
		{
			echo $adam->controlOutput($value["Adresse"], $value["Port"],$value["Value"]);
		}
		$db->AckSwitchCommand($switchCommands);
	}	

}


?>