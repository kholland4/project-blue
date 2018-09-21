<?php
require "util.php";
require "prefs.php";
$conn = create_sql_connection();
$userid = authenticate($conn);
?>
<!DOCTYPE html>
<html>
<head>
<style>
#header {
  font-weight: bold;
}
table {
  margin:0px;
  border:0px;
  padding:0px;
  border-collapse:collapse;
}
tr {
  margin:0px;
  border:0px;
  padding:0px;
}
td {
  margin:0px;
  border:0px;
}
</style>
</head>
<body>
<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
  //TODO: validation
  $prefs = array();
  foreach($_POST as $prefID => $level) {
    $prefs[intval($prefID)] = intval($level);
  }
  $res = prefs_set_arr($conn, $userid, $prefs);
  if($res == true) { echo "Updated."; } else { echo "Error"; }
}
?>
<form action="prefsui.php" method="POST">
<table>
<?php
$prefs = prefs_get_arr($conn, $userid);
//$prefs = array(0 => 5);
foreach($prefs as $prefID => $level) {
  echo "<tr><td>" . htmlspecialchars($prefData[$prefID]["name"]) . "</td><td>";
  echo "<input type=\"range\" name=\"" . $prefID . "\" min=\"" . $PREF_LEVEL_MIN . "\" max=\"" . $PREF_LEVEL_MAX . "\" value=\"" . $level . "\"><span id=\"" . $prefID . "\">";
  echo "</tr>\n";
}
?>
</table>
<button>Update</button>
</form>
</body>
</html>
