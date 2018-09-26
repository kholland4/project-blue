<?php
require "util.php";
require "prefs.php";
$conn = create_sql_connection();
$userid = authenticate($conn);

if($_SERVER["REQUEST_METHOD"] == "POST") {
  //TODO: validation!!!
  $prefs = array();
  foreach($_POST as $prefID => $level) {
    $prefs[intval($prefID)] = intval($level);
  }
  $res = prefs_set_arr($conn, $userid, $prefs);
  if($res == true) {
    header("Location: " . $BASE_URL);
    echo "Updated.";
  } else {
    echo "Error saving.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Your Interests - <?php echo $DISPLAY_NAME; ?></title>
<script src="prefsui.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="prefsui.css">
</head>
<body>
  <div id="stage1"></div>
  <div id="stage2">
    <form action="prefsui.php" method="POST" id="prefListForm">
      <table id="prefList" class="prefs"></table>
    </form>
    <div id="addMenuControl" onclick="toggleAddMenu();">More &#x25BE;</div>
    <div id="addMenu"></div>
    <button onclick="document.getElementById('prefListForm').submit();">Update</button>
    <a href="<?php echo $BASE_URL; ?>">Cancel</a>
  </div>
</body>
</html>
