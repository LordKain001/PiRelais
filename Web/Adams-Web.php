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

<h2>JavaScript in Body</h2>

<p id="demo">A Paragraph.</p>

<button type="button" onclick="myFunction()">Try it</button>

<script>
function myFunction() {
    document.getElementById("demo").innerHTML = "Paragraph changed.";
}
</script>

</body>
</html> 