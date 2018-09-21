<?php
require "util.php";
require "prefs.php";
$conn = create_sql_connection();
$userid = authenticate($conn);
?>
<!DOCTYPE html>
<html>
<head>
<script src="prefsui.js" type="text/javascript"></script>
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
    //TODO: validation!!!
    $prefs = array();
    foreach($_POST as $prefID => $level) {
      $prefs[intval($prefID)] = intval($level);
    }
    $res = prefs_set_arr($conn, $userid, $prefs);
    if($res == true) { echo "Updated."; } else { echo "Error"; }
  }
  ?>
  <form action="prefsui.php" method="POST" id="prefListForm">
    <table id="prefList">
      <?php
      $prefs = prefs_get_arr($conn, $userid);
      foreach($prefs as $prefID => $level) {
        echo "<tr id=\"prefRow" . $prefID . "\">\n";
        echo "<td>" . htmlspecialchars($prefData[$prefID]["name"]) . "</td>";
        echo "<td><input type=\"range\" name=\"" . $prefID . "\" min=\"" . $PREF_LEVEL_MIN . "\" max=\"" . $PREF_LEVEL_MAX . "\" value=\"" . $level . "\"></td>\n";
        echo "<td><button class=\"delPref\" onclick=\"delPref(" . $prefID . ");\" type=\"button\"></button></td>";
        echo "</tr>\n";
      }
      ?>
    </table>
  </form>
  <div id="addMenu">
    <select id="addMenuSelect">
      <option value="-1" selected>Choose</option>
    </select>
    <button onclick="addPref();">Add</button>
  </div>
  <button onclick="document.getElementById('prefListForm').submit();">Update</button>
  </body>
</html>
