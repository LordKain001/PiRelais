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
      return $sqlAdams = $this->GetMySqlData("Adam");
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
