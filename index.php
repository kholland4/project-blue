<?php
require "util.php";
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $DISPLAY_NAME; ?></title>
<link rel="stylesheet" type="text/css" href="inButtStyle.css">   
<link rel="stylesheet" type="text/css" href="indexStyle.css">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
</head>
<body>
  <div class ="slideContainer">
    <div id="slide1">
      <div id="main">
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
        echo "<a href='" . $BASE_URL . "userlist.php'>The List</a>";
      } else {
        // echo "<li class='liLogo'><img class='logo' src='img/huugsClear.svg' style='max-width:300px;'></li>\n"; 
        // echo "<a href='" . $BASE_URL . "login.php'><button>Log in</button></a><br>\n";
        // echo "<a href='" . $BASE_URL . "register.php'><button>Register</button></a>";
        header ( "Location:" . $BASE_URL . "login.php");
      }
      close_sql_connection($conn);
      ?>         
      </div>
    </div>
  </div>
</body>
</html>
