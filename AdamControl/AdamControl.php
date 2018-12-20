<?php

include __DIR__ . "/../TtyControl/TtyControl.php";


if (!class_exists('adamControl')) {


	class adamControl
	{
		public $ComPort;
		public $adams = array();
		//public $data = "";

		function __construct($ttyDevice)
		{			
			$this->ComPort =  new ttyControl($ttyDevice);
		}

		public function getStatus($adress = 0)
		{
			$log = "";
			if (($adress == 0) && (!is_null($this->adams)))
			{
				
				foreach ($this->adams as  &$value) 
				{
					$data = sprintf("$%02X6\r",$value["adress"]);
					$received = $this->adamCommand($data);
					$value["value"] = $this->interpretDigitalDataIn($received, $value["type"]);

					$log .= "Sent [ " . trim($data) . " ] received:[ " . $received . " ] -->value= " . $value["value"] . "\r\n";
		
				}
							
			}					
			else
			{
				$data = sprintf("$%02X6\r",$adress);
				$received = $this->adamCommand($data);
				$log .= "Sent [ " . trim($data) . " ] received:[ " . $received . " ]\r\n";	

			}
			

			return $log;
		}


		public function findAdams($startAdress = 1, $endAdress = 1)
		{
			
			for ($i=$startAdress; $i <= $endAdress; $i++) 
			{ 
				$data = "$" . sprintf("%02X",$i) . "M\r";
				$received = $this->adamCommand($data);
				$expr = "/!". sprintf("%02X",$i) . "..../";
				echo "looking for:" . $expr . " in " . $received . "\r\n";
				if (!empty($received)) 
				{					
					if (preg_match($expr, $received)) 
					{
						$adam = array(
							'adress' => substr($received, 1,2),
							'type' => substr($received, 3,4),
							 );
						array_push($this->adams, $adam);
					}				
				}		
				
			}
		}

		public function reportAdams($verbose = 0, $type = "")
		{
			$this->getStatus(0);
			$value = $this->adams;
			if (!empty($type)) 
			{
				$value = array_filter($value, function($v) use ($type) 
					{
						return ($v["type"] == $type);
					});
			}



			switch ($verbose) {
				case 0:
					break;
					
				case 1:
					var_dump($value);
					break;
				
				default:
					# code...
					break;
			}

			return $value;
		}




		public function controlOutput($adress, $outputnumber,$value)
		{
			$data = "#" . sprintf("%02X",$adress) . "1" . $outputnumber ."0" . $value ."\r";			
			$received = $this->adamCommand($data);
			$log = "Sent [ " . trim($data) . " ] received:[ " . $received . " ]\r\n";

			return $log;		
		}

		private function interpretDigitalDataIn($received, $type)
		{
			$value = $received;
			switch ($type) {
				case '4060':
					$value = sprintf("%08b",base_convert(substr($received,2,1), 16, 10));
					break;
				case '4053':
					$value = sprintf("%08b",base_convert(substr($received,1,2), 16, 10));
					$value .= ";";
					$value .= sprintf("%08b",base_convert(substr($received,3,2), 16, 10));
					break;
				
				default:
					
					break;
			}
			return $value;
		}

		private function adamCommand($data)
		{
			$received = "";
			$this->ComPort->writeData($data);
			usleep(10000);
			$received = trim($this->ComPort->getData());
			return $received;
		}

		
		function __destruct()
		{

		
		}	
	}
	
}



//$<Adresse>6...get Status
//#<Adresse>1<Port>0<Value>---switch Relais
?>

