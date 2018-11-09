<?php
require "util.php";
$conn = create_sql_connection();
//TODO: just use global $conn
$userid = get_login_user($conn);
$userInfo = get_user_info($conn, $userid);
if($userid === null) {
  header("Location:" . $BASE_URL . "login.php");
}
close_sql_connection($conn);
?>         
<!DOCTYPE html>
<html> 
<head>
<title><?php echo $DISPLAY_NAME; ?></title> 
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="index.css">
<link rel="stylesheet" type="text/css" href="detailpopup.css">
<link rel="stylesheet" type="text/css" href="header.css">
<link rel="stylesheet" type="text/css" href="app.css">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
</head>
<body>
  <div class="topPart">
    <div class="menuContainer" onclick="toggleMenu(this)">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div> 
    </div>
    <div class="dropdown-content" id="myDropdown">
      <?php 
      echo "<a href='" . $BASE_URL . "userlist.php'>The List</a>";
      echo "<a href='#'>Chatting</a>";
      echo "<a href='#'>Forums</a>";
      echo "<a href='" . $BASE_URL . "prefsui.php'>Your interests</a>\n";
      echo "<a href='" . $BASE_URL . "logout.php'>Log out</a>";
      ?>
    </div>
  </div>
  <div class="main">
    <div id="userList"></div>
  </div>
  <div class="bottom">
    <div id="mainUser">
      <img class="detailIconMain" src="img/pp.png">
      <p class="usersName">
        <?php 
          echo $userInfo["firstname"]." ".$userInfo["lastname"];
        ?></p>
    </div>
  </div>
    <div id="detailPopup">
    <div class="appHeader">
      <!-- TODO [Kyle]: use a X button on the right side instead -->
      <a class="backButton" href="javascript:hideDetail();"><i class="fas fa-2x fa-angle-left"></i></a>
      <span class="headerText" id="detailPopupHeaderText"></span>
    </div>
    <div id="detailPopupInner"></div>
  </div>
<script src="index.js" type="text/javascript"></script>
<script src="matchui.js" type="text/javascript"></script>
<script src="detailpopup.js" type="text/javascript"></script>
<script src="util.js" type="text/javascript"></script>
</body>
</html>
