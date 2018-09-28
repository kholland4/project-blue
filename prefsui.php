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
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <div id="stage1">
    <div id="stage1List" class="stage1"></div>
    <button onclick="initStage2();">Next</button>
  </div>
  <div id="stage2">
    <form action="prefsui.php" method="POST" id="prefListForm" class="stage2">
      <table id="prefList" class="stage2 prefs"></table>
    </form>
    <div id="addMenuControl" class="stage2" onclick="toggleAddMenu();">More &#x25BE;</div>
    <div id="addMenu" class="stage2"></div>
    <button onclick="document.getElementById('prefListForm').submit();" class="stage2">Update</button>
    <a href="<?php echo $BASE_URL; ?>" class="stage2">Cancel</a>
  </div>
</body>
</html>
