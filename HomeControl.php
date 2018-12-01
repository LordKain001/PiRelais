<?php

include "AdamControl.php";
include "./HomeControl/HomeControlDatabase.php"

	

passthru("sudo chmod 777 -R ../CCSMiner");

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
		if (!file_exists("/etc/systemd/system/HomeControl.service"))
		{
			passthru("sudo cp -v ccsMiner.service /etc/systemd/system");
		}

		if (!file_exists("/etc/systemd/system/multi-user.target.wants/HomeControl.service"))
		{
			passthru("sudo cp -v ccsMiner.service /etc/systemd/system/multi-user.target.wants");
		}

		
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

		
while (1) {
   
	
}

?>