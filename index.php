<?php
require "util.php";
$conn = create_sql_connection();
//TODO: just use global $conn
$userid = get_login_user($conn);
if($userid === null) {
  header("Location:" . $BASE_URL . "login.php");
}
$userInfo = get_user_info($conn, $userid);
close_sql_connection($conn);
?>         
<!DOCTYPE html>
<html> 
<head>
<title><?php echo $DISPLAY_NAME; ?></title>
<?php import("includes.html"); ?>
<link rel="stylesheet" type="text/css" href="index.css">
<link rel="stylesheet" type="text/css" href="detailpopup.css">
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
      echo "<a href='" . $BASE_URL . "userlist.php'>User List</a>";
      echo "<a href='#'>Chatting</a>";
      echo "<a href='" . $BASE_URL . "prefsui.php'>Your Interests</a>\n";
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
  <?php import("detailPopup.html"); ?>
<script src="index.js" type="text/javascript"></script>
<script src="matchui.js" type="text/javascript"></script>
<script src="detailpopup.js" type="text/javascript"></script>
<script src="util.js" type="text/javascript"></script>
</body>
</html>
