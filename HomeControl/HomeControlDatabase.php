<?php



if (!class_exists('homeControlDatabase')) 
{
  class homeControlDatabase
  {
    public $mysqli;
  

    function __construct()
    {     
      $this->mysqli = new mysqli("127.0.0.1", "root", "passwort", "HomeControl");
    }

    public function createEvent($eventType, $adam)
    {
      $sql="";
      switch ($eventType) {
        case 'DataIn':
          $sql .= "INSERT INTO DataIn (Type,Adresse,Data) VALUES ('$adam[type]','$adam[adress]','$adam[value]');\n";
          break;

        case 'Switch':
          
          break;
        
        default:
          # code...
          break;
      }
      if (!empty($sql)) 
      {
        $this->multiquerry($sql);
      }      
      
    }


    public function GetDataIn()
    {
      return $this->GetMySqlData("DataIn", "*", "`Done`=0");
    }

    public function AckDataIn($sqlGetDataIn)
    {
       $sql="";

       foreach ($sqlGetDataIn as $key => $value) 
       {
         $sql .= "UPDATE DataIn  SET Done=1 WHERE Primekey='$value[Primekey]';\n";
       }                    
      
              
      if (!empty($sql)) 
      {
        $this->multiquerry($sql);
      }   
      
    }


 public function SetSwitchCommand($sqlSwitchCommands)
    {
       $sql="";

       foreach ($sqlSwitchCommands as $key => $value) 
       {
         $sql .= "INSERT INTO Switch (Adresse,Port,Value) VALUES ('$value[Adresse]','$value[Port]','$value[value]');\n";
       }                    
      
              
      if (!empty($sql)) 
      {
        $this->multiquerry($sql);
      }   
      
    }
    public function GetSwitchCommand()
    {
      return $sqlSwitchCommands = $this->GetMySqlData("Switch", "*", "`Done`=0");
    }

    public function AckSwitchCommand($switchCommands)
    {
       $sql="";

       foreach ($switchCommands as $key => $value) 
       {
         $sql .= "UPDATE Switch  SET Done=1 WHERE Primekey='$value[Primekey]';\n";
       }                    
      
              
      if (!empty($sql)) 
      {
        $this->multiquerry($sql);
      }   
      
    }
    


    public function updateAdams($adams)
    {
      $sql="";
      foreach ($adams as $key => $value) 
      {
        
        $sql .= "INSERT INTO Adam (Type,Adresse,Status) VALUES ('$value[type]','$value[adress]','$value[value]') ON DUPLICATE KEY UPDATE Status='$value[value]' ;\n";
      }
      $this->multiquerry($sql);        
    }

    public function reportAdams()
    {
      return $this->GetMySqlData("Adam");
    }

    private function multiquerry($sql="")
    {     
      
      if (!$this->mysqli->multi_query($sql)) {
        echo "Multi query failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
      }

      do {
        if ($res = $this->mysqli->store_result()) {
          return $res->fetch_all(MYSQLI_ASSOC);
          
          $res->free();
        }
      } while ($this->mysqli->more_results() && $this->mysqli->next_result());

    }

    private function GetMySqlData($table="",$Select="*",$Where="1")
    {
      $sql ="";
      if ($this->mysqli->connect_errno)
      {
          echo "Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
      }
      $sql .= "SELECT $Select FROM `$table` WHERE $Where;\n";
      
      $sqlData = $this->mysqli->query($sql);

      return $sqlData->fetch_all(MYSQLI_ASSOC);
    }
  }
}


?>
