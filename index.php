<?php
require "util.php";
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $DISPLAY_NAME; ?></title>
<link rel="stylesheet" type="text/css" href="indexStyle.css">
<meta name="viewport" content="width=device-widht, initial-scale=1">
</head>
<body>
  <!-- slide container -->
  <div class ="slideContainer">
    <!-- images -->
    <div id="slide1" class="mySlides fade">
      <li style="display: flex; flex-wrap: wrap; margin-bottom: 0px; justify-content: center;">
        <img class="logo" src="img/huugsWhite.png" style="max-width: 300px;" alt="<?php echo $DISPLAY_NAME; ?>"> 
      </li>
      <?php
      $conn = create_sql_connection();
      //TODO: just use global $conn
      $userid = get_login_user($conn);
      if($userid !== null) {
        $name = get_user_name($conn, $userid);
        echo "<span>Hello, $name!</span><br>\n";
        echo "<a href='" . $BASE_URL . "prefsui.php'>Your interests</a><br>\n";
        echo "<a href='" . $BASE_URL . "matchui.php'>People like you</a><br>\n";
        echo "<a href='" . $BASE_URL . "logout.php'>Log out</a>";
      } else {
        echo "<a href='" . $BASE_URL . "login.php'><button>Log in</button></a><br>\n";
        echo "<a href='" . $BASE_URL . "register.php'><button>Register</button></a>";
      }
      close_sql_connection($conn);
      ?>        
    </div>
  </div>

</body>
</html>
