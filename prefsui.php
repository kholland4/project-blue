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
<link rel="stylesheet" type="text/css" href="prefsui.css">
<link rel="stylesheet" type="text/css" href="header.css">
<link rel="stylesheet" type="text/css" href="inButtStyle.css">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
</head>
<body>
  <div class="appHeaderCont">
    <div id="appHeader">
      <a id="backButton" href=""><i class="fas fa-2x fa-angle-left"></i></a>
      <span id="headerText"></span>
    </div>
  </div>
  <div id="stage1">
    <div id="stage1List" class="stage1"></div>
    <div class="forButton">
      <button onclick="initStage2();">Next</button><br>
    </div>
    <p style="margin: .5em;color: #8e8e8e;font-size: 15px;">Suggestions? We want to hear them! Text: (xxx) xxx-xxx</p>
  </div>
  <div id="stage2">
    <form action="prefsui.php" method="POST" id="prefListForm" class="stage2">
      <table id="prefList" class="stage2 prefs"></table>
    </form>
    <div id="addMenuControl" class="stage2" onclick="toggleAddMenu();">More &#x25BE;</div>
    <div id="addMenu" class="stage2"></div>
    <div class="forButton">
      <button onclick="document.getElementById('prefListForm').submit();" class="stage2">Update</button><br>
    </div>
    <p style="margin: .5em;color: #8e8e8e;font-size: 15px;">Suggestions? We want to hear them! Text: (801) 810-1766</p> 
  </div>
  <script src="prefsui.js" type="text/javascript"></script>
</body>
</html>
