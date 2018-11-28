
<?php

if (!class_exists('ttyControl')) {

	class ttyControl
	{
		public $stream;

		function __construct($ttyDevice)
		{
		exec("stty -F " . $ttyDevice . " raw -echo -echoe -echok -echoctl -echoke");
		$this->stream = fopen($ttyDevice, "r+b");
		stream_set_blocking($this->stream, false);
		}

		public function getData()
		{
			$buffer = "";
			$buffer .= stream_get_contents($this->stream);

			if ($buffer == "") {
				
			}
			return $buffer;
		}

		public function writeData($data="wrong Data")
		{
			fwrite($this->stream, $data);
						
		}

		
		function __destruct()
		{
		
		fclose($this->stream);
		}
		
		
		
		

	}
	
}


$comPort = new ttyControl("/dev/ttyUSB0");

while (true) {
	sleep(10);
	echo $comPort->getData();	# code...
}











?>