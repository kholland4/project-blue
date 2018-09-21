<?php
$DISPLAY_NAME = "Project Blue";
$BASE_URL = "/project-blue/";

function create_sql_connection() {
  $conn = mysqli_connect("localhost", "php", "password", "project-blue");
  if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  mysqli_set_charset($conn, "utf8mb4");
  return $conn;
}

function close_sql_connection($conn) {
  mysqli_close($conn);
}

function validate_input($required, $friendly_name, $index, $min_length, $max_length, $regex_pattern, $redirect = "register.html") {
  if(!array_key_exists($index, $_POST)) {
    if(!$required) {
      $GLOBALS["user_data"][$index] = null;
    } else {
      //if($redirect != null) { header("Refresh: 1; url=$redirect"); }
      exit("Error: No $friendly_name specified");
    }
    return;
  }
  if(!(strlen($_POST[$index]) <= $max_length && strlen($_POST[$index]) >= $min_length)) {
    if(!$required && $_POST[$index] == "") {
      $GLOBALS["user_data"][$index] = null;
    } else {
      //if($redirect != null) { header("Refresh: 1; url=$redirect"); }
      exit("Error: " . ucfirst($friendly_name) . " is too long or too short");
    }
    return;
  }
  if(!preg_match($regex_pattern, $_POST[$index])) {
    //if($redirect != null) { header("Refresh: 1; url=$redirect"); }
    exit("Error: " . ucfirst($friendly_name) . " contains invalid characters");
  }
  $GLOBALS["user_data"][$index] = $_POST[$index];
}

function get_login_user($conn) {
  if(!array_key_exists("sessionid", $_COOKIE)) {
    return null;
  }
  $stmt=mysqli_stmt_init($conn);
  $stmt->prepare("SELECT * FROM sessions WHERE sessionid=? LIMIT 1");
  $stmt->bind_param('s', $_COOKIE["sessionid"]);
  $stmt->execute();
  $result=mysqli_stmt_get_result($stmt);
  if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $stmt->close();
    return $row["userid"];
  } else {
    $stmt->close();
    return null;
  }
  $stmt->close();
}

function get_user_info($conn, $userid) {
  $stmt=mysqli_stmt_init($conn);
  $stmt->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
  $stmt->bind_param('i', $userid);
  $stmt->execute();
  $result = mysqli_stmt_get_result($stmt);
  if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $stmt->close();
    return $row;
  } else {
    $stmt->close();
    return null;
  }
  $stmt->close();
}

function get_user_name($conn, $userid) {
  $stmt = mysqli_stmt_init($conn);
  $stmt->prepare("SELECT username, firstname, lastname FROM users WHERE id=? LIMIT 1");
  $stmt->bind_param('i', $userid);
  $stmt->execute();
  $result = mysqli_stmt_get_result($stmt);
  if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $stmt->close();
    if($row["firstname"] != "" && $row["firstname"] != null) { //should never be blank - just null - but just in case
      return str_replace("\"", "\\\"", htmlspecialchars($row["firstname"]));
    } else if($row["lastname"] != "" && $row["lastname"] != null) {
      return str_replace("\"", "\\\"", htmlspecialchars($row["lastname"])); //"Mr.", etc.?
    } else {
      return str_replace("\"", "\\\"", htmlspecialchars($row["username"])); //just to be safe
    }
  } else {
    $stmt->close();
    return null;
  }
  $stmt->close();
}

function authenticate($conn) {
  $userid = get_login_user($conn);
  if($userid === null) {
    header("Location: " . $BASE_URL . "login.php"); //TODO: auto redirect back
    exit();
  } else {
    return $userid;
  }
}
?>
