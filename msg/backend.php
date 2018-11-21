<?php
require "../util.php";
$conn = create_sql_connection();
$userid = authenticate($conn);

if(!array_key_exists("target", $_GET)) {
  http_response_code(400);
  exit();
}
if(!is_numeric($_GET["target"])) {
  http_response_code(400);
  exit();
}

$target_userid = intval($_GET["target"]);

if(!array_key_exists("mode", $_GET)) {
  http_response_code(400);
  exit();
}
$mode = $_GET["mode"];
/*
Modes:
  full: full message history
*/
if(!in_array($mode, array("full"))) {
  http_response_code(400);
  exit();
}

if($mode == "full") {
  //load messages
  $messages = array();

  $stmt = mysqli_stmt_init($conn);
  $stmt->prepare("SELECT src, time, content FROM messages WHERE (src=? AND dest=?) OR (dest=? AND src=?) ORDER BY time ASC");
  $stmt->bind_param('iiii', $userid, $target_userid, $userid, $target_userid);
  $stmt->execute();
  $result = mysqli_stmt_get_result($stmt);
  $len = mysqli_num_rows($result);
  for($i = 0; $i < $len; $i++) {
    $row = mysqli_fetch_assoc($result);
    $data = array(
      "src" => $row["src"],
      "time" => $row["time"], //TODO convert to UNIX timestamp
      "content" => $row["content"]
    );
    array_push($messages, $data);
  }
  $stmt->close();
  
  echo json_encode($messages);
}
?>
