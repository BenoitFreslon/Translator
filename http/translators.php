<table>

<?php

include_once("connect_db.php");

$sql = "SELECT * FROM tr_translator JOIN tr_language ON tr_language.id_language = tr_translator.id_language ORDER BY code";
$res = mysqli_query($mysql_resource, $sql) or die ("error");
while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) {
	//echo $row;
	echo "<tr><td>".$row["nickname"]."</td><td>".$row["password"]."</td><td>".$row["code"]."</td><td>".$row["email"]."</td></tr>";
}

?>
</table>

<?php

$res = mysqli_query($sql) or die ("error");
while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) {
	//echo $row;
	echo $row["email"].";";
}

?>