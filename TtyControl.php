
<?php

if (!class_exists('ttyControl')) {

	class ttyControl
	{
		public $stream;
		public $data ="";

		function __construct($ttyDevice)
		{
		exec("stty -F " . $ttyDevice . " raw  -echo -echok -echoe onlret inlcr icrnl");
		$this->stream = fopen($ttyDevice, "r+b");
		stream_set_blocking($this->stream, false);
		}

		public function getData()
		{
			$buffer = "";
			$buffer .= fgets($this->stream);
			if ($buffer != "") {
				//$buffer .= "\n";	# code...
			}
			
			
			$this->data .=$buffer;

			return $buffer;
		}

		public function writeData($data="wrong Data")
		{
			fwrite($this->stream, $data);
						
		}

		
		function __destruct()
		{

		var_dump($this);
		
		fclose($this->stream);
		}
		

		

	}
	
}















?>