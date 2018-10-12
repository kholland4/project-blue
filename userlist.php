<?php
require "util.php";
$conn = create_sql_connection();
$userid = authenticate($conn);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="userlist.css">
<link rel="stylesheet" type="text/css" href="header.css">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<script src="userlist.js" type="text/javascript"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <div id="appHeader">
    <a id="backButton" href="javascript:history.go(-1);"><img src="img/arrow.png" class="fas fa-2x fa-angle-left"></a>
    <span id="headerText">All Users</span>
  </div>
  <div id="main">
    <div id="userList"></div>
  </div>
  <div style="display: flex;justify-content: center;"><img id="hDots" src="img/huugsDots.png"></div>
</body>
</html>
