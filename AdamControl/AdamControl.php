<?php

include "TtyControl.php";

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
					$this->ComPort->writeData($data);
					sleep(1);
					$received = trim($this->ComPort->getData());
					$log .= "Sent [ " . trim($data) . " ] received:[ " . $received . " ]\r\n";	
					$value["value"] = $received;
					sleep(0.2);
				}
							
			}					
			else
			{
				$data = sprintf("$%02X6\r",$adress);
				$this->ComPort->writeData($data);
				sleep(1);
				$received = trim($this->ComPort->getData());
				$log .= "Sent [ " . trim($data) . " ] received:[ " . $received . " ]\r\n";	

			}
			

			return $log;
		}


		public function findAdams($startAdress = 1, $endAdress = 1)
		{
			
			for ($i=$startAdress; $i <= $endAdress; $i++) 
			{ 
				$data = "$" . sprintf("%02X",$i) . "M\r";
				$this->ComPort->writeData($data);	
				sleep(1);
				$received = $this->ComPort->getData();
				$expr = "/!". sprintf("%02X",$i) . "..../";
				echo "loocking for:" . $expr . " in " . $received . "\r\n";
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

		public function reportAdams($verbose = 0)
		{
			$this->getStatus(0);
			$value = $this->adams;
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
			$this->ComPort->writeData($data);
			sleep(1);
			$received = trim($this->ComPort->getData());
			$log = "Sent [ " . trim($data) . " ] received:[ " . $received . " ]\r\n";

			return $log;		
		}

		
		function __destruct()
		{

		
		}	
	}
	
}



//$<Adresse>6...get Status
//#<Adresse>1<Port>0<Value>---switch Relais
?>

