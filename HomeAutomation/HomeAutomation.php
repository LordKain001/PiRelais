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
	

}


?>