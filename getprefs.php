<?php
require "util.php";
require "prefs.php";
$conn = create_sql_connection();
$userid = get_login_user($conn);
if($userid == null) {
  http_response_code(401);
  exit();
}

$prefs = prefs_get_arr($conn, $userid);
$data = array();
foreach($prefs as $prefID => $level) {
  array_push($data, array("id" => $prefID, "level" => $level));
}
echo json_encode($data);
?>
