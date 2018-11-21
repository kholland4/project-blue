<?php
require "util.php";
$conn = create_sql_connection();

if(!array_key_exists("userid", $_GET)) {
  http_response_code(400);
  exit();
}
if(!is_numeric($_GET["userid"])) {
  http_response_code(400);
  exit();
}

$target_userid = intval($_GET["userid"]);

$stmt=mysqli_stmt_init($conn);
$stmt->prepare("SELECT profile_photo FROM users WHERE id=? LIMIT 1");
$stmt->bind_param('i', $target_userid);
$stmt->execute();
$result = mysqli_stmt_get_result($stmt);
if(mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  if($row["profile_photo"] != null) {
    echo base64_decode($row["profile_photo"]); //FIXME
  } else {
    readfile("img/pp.png");
  }
} else {
  readfile("img/pp.png");
}
$stmt->close();

close_sql_connection($conn);
?>
