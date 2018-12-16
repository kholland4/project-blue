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
  <div class="main">
    <div id="userList"></div>
  </div>
  <div class="bottom">
    <div id="mainUser">
      <i class="fas fa-comments messagePage"></i>
      <img class="detailIconMain" src="img/pp.png">
      <i class="fas fa-list-ul userlistPage"></i>
    </div>
  </div>
  <?php import("detailPopup.html"); ?>
<script src="index.js" type="text/javascript"></script>
<script src="matchui.js" type="text/javascript"></script>
<script src="detailpopup.js" type="text/javascript"></script>
<script src="util.js" type="text/javascript"></script>
</body>
</html>
