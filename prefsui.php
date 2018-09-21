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
  foreach($_POST as $pref => $level) {
    $prefs[$pref] = intval($level);
  }
  $res = prefs_set_arr($conn, $userid, $prefs);
  if($res == true) { echo "Updated."; } else { echo "Error"; }
}
?>
<form action="prefsui.php" method="POST">
<table>
<?php
$prefs = prefs_get_arr($conn, $userid);
if(!isset($prefs["test"])) { $prefs["test"] = 0; }
foreach($prefs as $pref => $level) {
  echo "<tr><td>" . htmlspecialchars($pref) . "</td><td>";
  for($i = $PREF_LEVEL_MIN; $i <= $PREF_LEVEL_MAX; $i++) {
    $sel = ""; if($i == $level) { $sel = " checked"; }
    echo "<input type=\"radio\" name=\"" . addslashes($pref) . "\" value=\"" . $i . "\"" . $sel . ">" . $i;
  }
  echo "</tr>\n";
}
?>
</table>
<button>Update</button>
</form>
</body>
</html>
