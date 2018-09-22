<?php
require "util.php";
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Sign In - <?php echo $DISPLAY_NAME; ?></title>
<link rel="stylesheet" type="text/css" href="loginStyle.css">
</head>
<body>
<div id="container">
<form id="f1" action="login.php" method="POST">
<img src="huugsClear.png">
<input type="text" name="username" minlength="4" maxlength="100" placeholder="Username" required autofocus><br>
<input type="password" name="password" minlength="8" maxlength="64" placeholder="Password" required><br>
<button>Sign In</button>
</form>
<?php
if(isset($_POST["username"])) {
  $user_data = array();
  validate_input(true, "username", "username", 4, 100, "/^[A-Za-z0-9_\-]+$/");
  validate_input(true, "password", "password", 8, 64, "//");
  $conn = create_sql_connection();

  //Check for valid username/password
  $userid = null;
  $stmt=mysqli_stmt_init($conn);
  $stmt->prepare("SELECT * FROM users WHERE username=?");
  $stmt->bind_param('s', $user_data["username"]);
  $stmt->execute();
  $result = mysqli_stmt_get_result($stmt);
  if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $password_hash = hash("sha256", $row["password_salt"] . $user_data["password"], false);
    if($password_hash != $row["password"]) {
      $stmt->close();
      close_sql_connection($conn);
      exit("Invalid username/password");
    }
    $userid = $row["id"];
  } else {
    $stmt->close();
    close_sql_connection($conn);
    exit("Invalid username/password");
  }
  $stmt->close();
  $curr_userid = get_login_user($conn);
  if($curr_userid != null) {
    //Delete current session
    $stmt = mysqli_stmt_init($conn);
    $stmt->prepare("DELETE FROM sessions WHERE userid=? AND sessionid=? LIMIT 1");
    $stmt->bind_param('is', $curr_userid, $_COOKIE["sessionid"]);
    $stmt->execute();
    $result = mysqli_stmt_get_result($stmt);
    if(!$result) {
      //OK
    } else {
      echo "Error: " . mysqli_error($conn) . "\n";
    }
    $stmt->close();
  }
  $sessionid_in_use = true;
  for($i = 0; $i < 5 && $sessionid_in_use; $i++) {
    //Generate session ID
    $sessionid_ok = false;
    $sessionid = bin2hex(openssl_random_pseudo_bytes(64, $sessionid_ok));
    if(!$sessionid_ok) {
      close_sql_connection($conn);
      exit("Error: Unable to generate a cryptographically strong session ID");
    }
    //Ensure that session ID isn't already in use
    $stmt = mysqli_stmt_init($conn);
    $stmt->prepare("SELECT * FROM sessions WHERE sessionid=?");
    $stmt->bind_param('s', $sessionid);
    $stmt->execute();
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result) === 0) {
      $sessionid_in_use = false;
    }
    $stmt->close();
  }
  if($sessionid_in_use) {
    close_sql_connection($conn);
    exit("Error: Unable to generate a unique session ID");
  }
  //Add session
  $stmt=mysqli_stmt_init($conn);
  $stmt->prepare("INSERT INTO sessions (userid, sessionid) VALUES (?, ?)");
  $stmt->bind_param('is', $userid, $sessionid);
  $stmt->execute();
  $result=mysqli_stmt_get_result($stmt);
  if(!$result) {
    setcookie("sessionid", $sessionid, 0, $BASE_URL);
    header("Location: " . $BASE_URL);
    echo "Login successful.";
  } else {
    echo "Error: ".mysqli_error($conn)."\n";
  }
  $stmt->close();
  close_sql_connection($conn);
}
?>
</div>
</body>
</html>
