<?php
require "util.php";
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $DISPLAY_NAME; ?></title> 
<link rel="stylesheet" type="text/css" href="app.css">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="buttonDiv"><button class="headerButt"><a href='<?php echo "" . $BASE_URL . "#"?>'>Messages</a></button></div>
      <div class="buttonDiv"><button class="headerButt"><a href='<?php echo "" . $BASE_URL . "userlist.php"?>'>The list</a></button></div>
    </div> 
    <div>
      <?php
      $conn = create_sql_connection();
      //TODO: just use global $conn
      $userid = get_login_user($conn);
      if($userid !== null) {
        $name = get_user_name($conn, $userid);
        echo "<span>Hello, $name!</span><br>\n";
        echo "<a href='" . $BASE_URL . "prefsui.php'>Your interests</a><br>\n";
        echo "<a href='" . $BASE_URL . "matchui.php'>People like you</a><br>\n";
        echo "<a href='" . $BASE_URL . "logout.php'>Log out</a><br>";
        echo "<a href='" . $BASE_URL . "profileui.php'>Your Profile</a>";
      }
      close_sql_connection($conn);
      ?>    
    </div>
  </div>
</body>
</html>
