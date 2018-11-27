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
<link rel="stylesheet" type="text/css" href="inButtStyle.css">
<?php import("includes.html"); ?>
</head>
<body>
  <div class="appHeaderCont">
    <div id="appHeader">
      <a id="backButton" href="javascript:history.go(-1);"><i class="fas fa-2x fa-angle-left"></i></a>
      <span id="headerText"><span class="firstW">Your </span><span class="secondW">Interests</span></span>
    </div>
  </div>
  <div id="stage1">
    <div id="stage1List" class="stage1"></div>
    <div class="forButton">
      <button onclick="submit();">Submit</button><br>
    </div>
    <p style="margin: .5em;color: #8e8e8e;font-size: 15px;">Suggestions? We want to hear them! email: HuugsCo@gmail.com</p>
  </div>
  <div id="levelPopup">
    <div id="levelPopupTitle">How much do you like <span id="levelPopupName"></span>?</div>
    <div id="levelPopupButtons"></div>
  </div>
  <script src="prefsui.js" type="text/javascript"></script>
</body>
</html>
