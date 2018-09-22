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
<link rel="stylesheet" type="text/css" href="prefsui.css">
</head>
<body>
  <a href="<?php echo $BASE_URL; ?>">Home</a><br>
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
    <table id="prefList" class="prefs">
      <?php
      $prefs = prefs_get_arr($conn, $userid);
      foreach($prefs as $prefID => $level) {
        echo "<tr id=\"prefRow" . $prefID . "\" class=\"prefs\">\n";
        echo "<td class=\"prefs\"><div class=\"prefIconOuter\"><img class=\"prefIcon\" src=\"" . $prefData[$prefID]["image"] . "\"><span class=\"prefIconCaption\">" . htmlspecialchars($prefData[$prefID]["name"]) . "</span></td>";
        echo "<td class=\"prefs\"><input type=\"range\" name=\"" . $prefID . "\" min=\"" . $PREF_LEVEL_MIN . "\" max=\"" . $PREF_LEVEL_MAX . "\" value=\"" . $level . "\"></td>\n";
        echo "<td class=\"prefs\"><button class=\"delPref\" onclick=\"delPref(" . $prefID . ");\" type=\"button\"></button></td>";
        echo "</tr>\n";
      }
      ?>
    </table>
  </form>
  <div id="addMenu">
    <!--<select id="addMenuSelect">
      <option value="-1" selected>Choose</option>
    </select>
    <button onclick="addPref();">Add</button>-->
  </div>
  <button onclick="document.getElementById('prefListForm').submit();">Update</button>
  </body>
</html>
