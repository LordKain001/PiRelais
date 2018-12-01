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

    public function updateAdmas($adams)
    {
      $sql="";
      foreach ($adams as $key => $value) 
      {
        
        $sql .= "INSERT INTO Adam (Type,Adresse,Status) VALUES ('$value[type]','$value[adress]','$value[value]') ON DUPLICATE KEY UPDATE Status='$value[value]' ;\n";


      }
        echo $sql;
       if (!$this->mysqli->multi_query($sql))
       {
          echo "Multi query failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
        } 

      do {
        if ($res = $this->mysqli->store_result()) {
          return $res->fetch_all(MYSQLI_ASSOC);
          
          $res->free();
        }
      } while ($this->mysqli->more_results() && $this->mysqli->next_result());
    }
  }
}


?>
