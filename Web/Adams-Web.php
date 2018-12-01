<?php

include '../HomeControl/HomeControlDatabase.php';


$db = new homeControlDatabase();


$data = $db->reportAdams();

echo("<PRE>");
var_dump($data);
echo("</PRE>");



?>

<!DOCTYPE html>
<html>
<body>

<h1>My First Heading</h1>
<p>My first paragraph.</p>

</body>
</html>