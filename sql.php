<?php
require "util.php";
/*CREATE TABLE users (
id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(100) NOT NULL,
password CHAR(64) NOT NULL,
password_salt BINARY(64) NOT NULL,
firstname VARCHAR(100),
lastname VARCHAR(100),
email VARCHAR(100),
profile_photo MEDIUMBLOB,
reg_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
prefs MEDIUMTEXT
)*/
/*CREATE TABLE sessions (
id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
userid BIGINT NOT NULL,
sessionid VARCHAR(128) NOT NULL,
time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)*/
$conn = create_sql_connection();

$sql = "";
if(mysqli_query($conn, $sql)) {
  echo "Command successful";
} else {
  echo "Error: " . mysqli_error($conn);
}
mysqli_close($conn);
//echo preg_quote("~!@#$%^&*()_+-=[]\{}|;:<>?,./");
?>
