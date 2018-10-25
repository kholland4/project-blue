<?php
require "util.php";
$conn = create_sql_connection();
//TODO: just use global $conn
$userid = get_login_user($conn);
if($userid !== null) {
  header("Location:" . $BASE_URL . "app.php");
} else {
  header ( "Location:" . $BASE_URL . "login.php");
}
close_sql_connection($conn);
?>         
