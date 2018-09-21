<?php
require "util.php";
$conn = create_sql_connection();
$curr_userid = get_login_user($conn);
if($curr_userid == null) {
  echo "Error: You are not logged in";
} else {
  //Delete current session
  $stmt = mysqli_stmt_init($conn);
  $stmt->prepare("DELETE FROM sessions WHERE userid=? AND sessionid=?");
  $stmt->bind_param('is', $curr_userid, $_COOKIE["sessionid"]);
  $stmt->execute();
  $result = mysqli_stmt_get_result($stmt);
  if(!$result) {
    setcookie("sessionid", "", time() - 3600, $BASE_URL);
    header("Location: " . $BASE_URL);
    echo "Logout successful.";
  } else {
    echo "Error: " . mysqli_error($conn) . "\n";
  }
  $stmt->close();
}
close_sql_connection($conn);
?>
