
<?php

if (!class_exists('gpioControl')) {

	class gpioControl
	{
		public $portNumber;

		function __construct($portNumber)
		{
		exec("echo \"".$portNumber."\" > /sys/class/gpio/export");
		//echo("echo \"".$portNumber."\" > /sys/class/gpio/export");
		
		$this->portNumber = $portNumber;	
		}

		public function setDirection($dir)
		{
			switch ($dir) {
				case 'out':
					exec("echo 'out' > /sys/class/gpio/gpio". $this->portNumber . "/direction");
					break;

				case 'in':
					exec("echo 'in' > /sys/class/gpio/gpio". $this->portNumber . "/direction");
					break;
				
				default:
					# code...
					break;
			}
		}

		public function getValue($verbose=0)
		{
			switch ($verbose) {
				case 0:
					$value = exec("cat /sys/class/gpio/gpio". $this->portNumber . "/value");
					break;

				case 1:
					$value = "Gpio[". $this->portNumber ."] = " ;
					$value .= exec("cat /sys/class/gpio/gpio". $this->portNumber . "/value");
					$value .= "\n";
					break;
				
				default:
					# code...
					break;
			}

			return $value;
			
		}

		public function setValue($value)
		{
			if ($value == 0 ) {
				exec("echo '0' > /sys/class/gpio/gpio". $this->portNumber . "/value");
			}
			if ($value == 1 ) {
				exec("echo '1' > /sys/class/gpio/gpio". $this->portNumber . "/value");
			}
		}

		function __destruct()
		{
			echo "unexport Gpio" .$this->portNumber . "\n";
			exec("echo \"".$this->portNumber."\" > /sys/class/gpio/unexport");
		}
		
		
		
		

	}
	
}


for ($i=0; $i <20 ; $i++) { 
	$PiGpios[$i] = new gpioControl($i);
}

sleep(10);

foreach ($PiGpios as $key => $value) {
	$value->setDirection("out");
	$value->setValue(0);
	echo $value->getValue(1);
	$value->setValue(1);
	echo $value->getValue(1);

}

PHP_EOL;


?>