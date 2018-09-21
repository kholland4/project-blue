<?php
require "util.php";
$user_data = array();
validate_input(true, "username", "username", 4, 100, "/^[A-Za-z0-9_\-]+$/");
validate_input(true, "password", "password1", 8, 64, "//");
validate_input(true, "password confirmation", "password2", 8, 64, "//");
validate_input(false, "first name", "firstname", 1, 100, "/^[A-Za-z\-]+$/");
validate_input(false, "last name", "lastname", 1, 100, "/^[A-Za-z\-]+$/");
validate_input(false, "email address", "email", 6, 100, "/^[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-]+\.[a-z\.]+$/");
if($_POST["password1"] != $_POST["password2"]) {
  header("Refresh: 1; url=" . $BASE_URL . "register.html");
  exit("Error: Passwords don't match");
}
$conn = create_sql_connection();

//Check for duplicate username
$stmt = mysqli_stmt_init($conn);
$stmt->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
$stmt->bind_param('s', $user_data["username"]);
$stmt->execute();
$result = mysqli_stmt_get_result($stmt);
if(mysqli_num_rows($result) > 0) {
  $stmt->close();
  close_sql_connection($conn);
  header("Refresh: 1; url=register.html");
  exit("Error: The requested username is already in use");
}
$stmt->close();

//Add to database
$salt_ok = false;
$password_salt = openssl_random_pseudo_bytes(64, $salt_ok);
if(!$salt_ok) {
  close_sql_connection($conn);
  header("Refresh: 1; url=" . $BASE_URL . "register.html");
  exit("Error: Unable to generate cryptographically strong password salt");
}
$password_hash = hash("sha256", $password_salt . $user_data["password1"], false);
$stmt = mysqli_stmt_init($conn);
$stmt->prepare("INSERT INTO users (username, password, password_salt, firstname, lastname, email) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param('ssssss', $user_data["username"], $password_hash, $password_salt, $user_data["firstname"], $user_data["lastname"], $user_data["email"]);
$stmt->execute();
$result = mysqli_stmt_get_result($stmt);
if(!$result) {
  echo "Registration successful.<br>\n";
  echo "<a href='" . $BASE_URL . "'>Home</a>";
  //TODO: auto-login
} else {
  echo "Error: " . mysqli_error($conn) . "\n";
}
$stmt->close();
close_sql_connection($conn);
?>
