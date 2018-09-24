<?php
function match_get_raw($conn, $userid = null) {
  $stmt=mysqli_stmt_init($conn);
  $stmt->prepare("SELECT id, username, firstname, lastname, prefs FROM users");
  $stmt->execute();
  $result = mysqli_stmt_get_result($stmt);
  $rowCount = mysqli_num_rows($result);
  $data = array();
  for($i = 0; $i < $rowCount; $i++) {
    $row = mysqli_fetch_assoc($result);
    if($row["id"] != $userid) {
      array_push($data, array(
        "id" => $row["id"],
        "username" => $row["username"],
        "firstname" => $row["firstname"],
        "lastname" => $row["lastname"],
        "prefs" => json_decode($row["prefs"], true)
      ));
    }
  }
  $stmt->close();
  return $data;
}
?>
