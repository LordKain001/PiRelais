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


switch ($config["installStatus"]) {
	case '1':		
			passthru("sudo cp -v HomeAutomation.service /etc/systemd/system");		
			passthru("sudo cp -v HomeAutomation.service /etc/systemd/system/multi-user.target.wants");		
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


include "../HomeControl/HomeControlDatabase.php";


$db = new homeControlDatabase(); 

$switchCommands = array();
array_push($switchCommands, array("Adresse"=>"01","Port"=>0,"value"=>0));

$w1Sensors = scandir("/sys/bus/w1/devices");
$w1Sensors = array_filter($w1Sensors, "isW1Sensor");

while(1)
{
	usleep(10000);
	$dataIn = $db->GetDataIn();

	if (!empty($dataIn)) 
	{
		var_dump($dataIn);

		foreach ($dataIn as $key => $value) {
			if ($value["Adresse"] == "02") 
			{
				if ($value["Data"] == "11111111;11111110") 
				{
					$switchCommands[0]["value"] = 1 -$switchCommands[0]["value"];
					var_dump($switchCommands);
					$db->SetSwitchCommand($switchCommands);
				}
			
			}
		}




		$dataIn = $db->AckDataIn($dataIn);
	}
	


	$temps = GetAllW1SensorTemps($w1Sensors);
	if ($temps["28-041662c477ff"]["Temp"] >= 25.0) 
	{
		if ($switchCommands[0]["value"] != 0)
		{
			echo "not Heating \r";
			$switchCommands[0]["value"] = 0;
			$db->SetSwitchCommand($switchCommands);
		}
		
	}
	if ($temps["28-041662c477ff"]["Temp"] <= 22.0) 
	{
		if ($switchCommands[0]["value"] != 1)
		{
			echo "Heating \r";
			$switchCommands[0]["value"] = 1;
			$db->SetSwitchCommand($switchCommands);
		}
		
	}
	

}




function GetAllW1SensorTemps($w1Sensors)
{
	
	$temperatures = array();	


	foreach ($w1Sensors as $key) 
	{
		$path = "/sys/bus/w1/devices/" . $key . "/w1_slave";
		$sensorTemp = file_get_contents($path);
		$sensorTemp = substr($sensorTemp, strpos($sensorTemp, "t=") + 2);    
		$temperatures[$key]["Temp"] = $sensorTemp/ 1000;
		$temperatures[$key]["Time"] = time();
	}
	return $temperatures;
}



function isW1Sensor($value)
{
	$query = "28-";
	if(substr( $value, 0, strlen($query) ) === $query)
	{
		return true;
	}
}






?>