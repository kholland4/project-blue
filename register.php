<?php
require "util.php";
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Register - <?php echo $DISPLAY_NAME; ?></title>
<link rel="stylesheet" type="text/css" href="registerStyle.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div id="container">
<div id="header">Register</div>
<form action="register.php" method="POST">
<!--If you're wondering, all of this validation is done on the server side as well.-->
<table>
<tr>
  <!-- <td>Username:</td> -->
  <td><input placeholder="Username" type="text" name="username" minlength="4" maxlength="100" pattern="^[A-Za-z0-9_\-]+$" title="Letters, numbers, underscores, and hyphens only." required></td>
</tr>
<tr>
  <!-- <td>Password:</td> -->
  <td><input placeholder="Password" type="password" name="password1" minlength="8" maxlength="64" required></td>
</tr>
<tr>
  <!-- <td>Confirm password:</td> -->
  <td><input placeholder="Confirm password" type="password" name="password2" minlength="8" maxlength="64" required></td>
</tr>
<tr>
  <!-- <td>First name:</td> -->
  <td><input placeholder="First name" type="text" name="firstname" minlength="1" maxlength="100" pattern="^[A-Za-z\-]+$" title="Letters and hyphens only."></td>
</tr>
<tr>
  <!-- <td>Last name:</td> -->
  <td><input placeholder="Last name" type="text" name="lastname" minlength="1" maxlength="100" pattern="^[A-Za-z\-]+$" title="Letters and hyphens only."></td>
</tr>
<tr>
  <!-- <td>Email address:</td> -->
  <td><input placeholder="Email address" type="email" name="email" minlength="6" maxlength="100" pattern="^[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-]+\.[a-z\.]+$" title="Letters, numbers, underscores, hyphens, and periods."></td>
</tr>
</table>
<button>Register</button>
</form>
<?php
if(isset($_POST["username"])) {
  $user_data = array();
  validate_input(true, "username", "username", 4, 100, "/^[A-Za-z0-9_\-]+$/");
  validate_input(true, "password", "password1", 8, 64, "//");
  validate_input(true, "password confirmation", "password2", 8, 64, "//");
  validate_input(false, "first name", "firstname", 1, 100, "/^[A-Za-z\-]+$/");
  validate_input(false, "last name", "lastname", 1, 100, "/^[A-Za-z\-]+$/");
  validate_input(false, "email address", "email", 6, 100, "/^[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-]+\.[a-z\.]+$/");
  if($_POST["password1"] != $_POST["password2"]) {
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
    exit("Error: The requested username is already in use");
  }
  $stmt->close();

  //Add to users table
  $salt_ok = false;
  $password_salt = openssl_random_pseudo_bytes(64, $salt_ok);
  if(!$salt_ok) {
    close_sql_connection($conn);
    exit("Error: Unable to generate cryptographically strong password salt");
  }
  $password_hash = hash("sha256", $password_salt . $user_data["password1"], false);
  $stmt = mysqli_stmt_init($conn);
  $stmt->prepare("INSERT INTO users (username, password, password_salt, firstname, lastname, email, prefs) VALUES (?, ?, ?, ?, ?, ?, '{}')");
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
}
?>
</div>
</body>
</html>
