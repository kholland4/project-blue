<?php
require "util.php";
require "match.php";
$conn = create_sql_connection();
$userid = authenticate($conn);

$data = match_get_raw($conn);
$pics = match_get_pics($conn);
for($i = 0; $i < count($data); $i++) {
  $data[$i]["profile_photo"] = $pics[$i]["profile_photo"]; //FIXME this will probably break
}

header("Content-Type: text/json");
echo json_encode($data);
?>
