<?php
require "util.php";
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $DISPLAY_NAME; ?></title>
</head>
<body>
<span style="font-weight: bold;"><?php echo $DISPLAY_NAME; ?></span><br>
<?php
$conn = create_sql_connection();
//TODO: just use global $conn
$userid = get_login_user($conn);
if($userid !== null) {
  $name = get_user_name($conn, $userid);
  echo "<span>Hello, $name!</span><br>\n";
  echo "<a href='" . $BASE_URL . "prefsui.php'>Your interests</a><br>\n";
  echo "<a href='" . $BASE_URL . "logout.php'>Log out</a>";
} else {
  echo "<a href='" . $BASE_URL . "login.php'>Log in</a><br>\n";
  echo "<a href='" . $BASE_URL . "register.html'>Register</a>";
}
close_sql_connection($conn);
?>
</body>
</html>
