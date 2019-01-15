<?php
require "../util.php";
$conn = create_sql_connection();
$userid = authenticate($conn);

if(!array_key_exists("target", $_GET)) {
  http_response_code(400);
  echo "no target specified";
  exit();
}
if(!is_numeric($_GET["target"])) {
  http_response_code(400);
  echo "invalid target (non-numeric)";
  exit();
}

$target_userid = intval($_GET["target"]);

if(!array_key_exists("mode", $_GET)) {
  http_response_code(400);
  echo "no mode specified";
  exit();
}
$mode = $_GET["mode"];
/*
Modes:
  full: full message history
*/
if(!in_array($mode, array("full", "since", "send", "convlist"))) {
  http_response_code(400);
  echo "invalid mode";
  exit();
}

$since = 0;
if($mode == "since") {
  if(!array_key_exists("since", $_GET)) {
    http_response_code(400);
    echo "no 'since' parameter specified";
    exit();
  }
  if(!is_numeric($_GET["since"])) {
    http_response_code(400);
    echo "invalid 'since' parameter (non-numeric)";
    exit();
  }
  $since = intval($_GET["since"]);
}

if($mode == "full" || $mode == "since") {
  //All messages
  $messages = array();

  $stmt = mysqli_stmt_init($conn);
  if($mode == "full") {
    $stmt->prepare("SELECT src, time, content FROM messages WHERE (src=? AND dest=?) OR (dest=? AND src=?) ORDER BY time ASC");
    $stmt->bind_param('iiii', $userid, $target_userid, $userid, $target_userid);
  } else if($mode == "since") {
    $since_formatted = date("Y-m-d H:i:s", $since);
    $stmt->prepare("SELECT src, time, content FROM messages WHERE ((src=? AND dest=?) OR (dest=? AND src=?)) AND time > ? ORDER BY time ASC");
    $stmt->bind_param('iiiis', $userid, $target_userid, $userid, $target_userid, $since_formatted);
  }
  $stmt->execute();
  $result = mysqli_stmt_get_result($stmt);
  $len = mysqli_num_rows($result);
  for($i = 0; $i < $len; $i++) {
    $row = mysqli_fetch_assoc($result);
    $data = array(
      "src" => $row["src"],
      "time" => strtotime($row["time"]), //convert to UNIX timestamp
      "content" => $row["content"]
    );
    array_push($messages, $data);
  }
  $stmt->close();
  
  echo json_encode($messages);
} else if($mode == "send") {
  //Send a message
  if(!array_key_exists("message", $_POST)) {
    http_response_code(400);
    echo "no message specified";
    exit();
  }
  $message = $_POST["message"];
  if(strlen($message) <= 0 || strlen($message) > 5000) { //FIXME pick a proper limit and implement on client end as well
    http_response_code(400);
    echo "message is too short or long";
    exit();
  }
  
  $stmt = mysqli_stmt_init($conn);
  $stmt->prepare("INSERT INTO messages (src, dest, content) VALUES (?, ?, ?)");
  $stmt->bind_param('iis', $userid, $target_userid, $message);
  $stmt->execute();
  $result = mysqli_stmt_get_result($stmt);
  if(!$result) {
    //ok
  } else {
    http_response_code(500);
    echo "Error: " . mysqli_error($conn) . "\n";
  }
  $stmt->close();
} else if($mode == "convlist") {
  //Conversation list
  $messages = array();

  $stmt = mysqli_stmt_init($conn);
  $stmt->prepare("SELECT src, dest, time, content FROM messages WHERE dest=? OR src=? ORDER BY time ASC");
  $stmt->bind_param('ii', $userid, $userid);
  $stmt->execute();
  $result = mysqli_stmt_get_result($stmt);
  $len = mysqli_num_rows($result);
  for($i = 0; $i < $len; $i++) {
    $row = mysqli_fetch_assoc($result);
    $data = array(
      "src" => $row["src"],
      "dest" => $row["dest"],
      "time" => strtotime($row["time"]), //convert to UNIX timestamp
      "content" => $row["content"]
    );
    array_push($messages, $data);
  }
  $stmt->close();
  
  $conversations = array();
  foreach($messages as $message) {
    $target = $message["src"];
    if($message["src"] == $userid) {
      $target = $message["dest"];
    }
    
    $conversations[$target] = $message;
  }
  
  $conversations2 = array();
  foreach($conversations as $target => $lastMessage) {
    array_push($conversations2, array("target" => $target, "lastMessage" => $lastMessage));
  }
  
  echo json_encode($conversations2);
} else {
  http_response_code(400);
  echo "invalid mode";
  exit();
}

close_sql_connection($conn);
?>
