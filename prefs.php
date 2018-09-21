<?php
$prefDataRaw = json_decode(file_get_contents("prefdata.json"), true);
$PREF_LEVEL_MIN = $prefDataRaw["meta"]["PREF_LEVEL_MIN"];
$PREF_LEVEL_MAX = $prefDataRaw["meta"]["PREF_LEVEL_MAX"];
$PREF_LEVEL_DEFAULT = $prefDataRaw["meta"]["PREF_LEVEL_DEFAULT"];
$prefData = $prefDataRaw["data"];

function prefs_get($conn, $userid) {
  $stmt=mysqli_stmt_init($conn);
  $stmt->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
  $stmt->bind_param('i', $userid);
  $stmt->execute();
  $result = mysqli_stmt_get_result($stmt);
  if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $stmt->close();
    return $row["prefs"];
  } else {
    $stmt->close();
    return null;
  }
  $stmt->close();
}

function prefs_set($conn, $userid, $data) {
  $stmt = mysqli_stmt_init($conn);
  $stmt->prepare("UPDATE users SET prefs=? WHERE id=?");
  $stmt->bind_param('si', $data, $userid);
  $stmt->execute();
  $result = mysqli_stmt_get_result($stmt);
  if(!$result) {
    $stmt->close();
    return true;
  } else {
    $stmt->close();
    echo "Error: " . mysqli_error($conn) . "\n"; //FIXME
    return false;
  }
  $stmt->close();
}

function prefs_get_arr($conn, $userid) {
  $p = prefs_get($conn, $userid);
  if($p == null) { return null; }
  return json_decode($p, true);
}

function prefs_set_arr($conn, $userid, $data) {
  return prefs_set($conn, $userid, json_encode($data));
}
?>
