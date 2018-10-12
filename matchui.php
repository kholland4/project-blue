<?php
require "util.php";
$conn = create_sql_connection();
$userid = authenticate($conn);
?>
<!DOCTYPE html>
<html>
<head>
<title>Your Matches</title>
<link rel="stylesheet" type="text/css" href="matchui.css">
<link rel="stylesheet" type="text/css" href="header.css">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<script src="matchui.js" type="text/javascript"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <div id="appHeader">
    <a id="backButton" href="javascript:history.go(-1);"><img src="img/arrow.png" class="fas" > </a>
    <span id="headerText">Your Matches</span>
  </div>
  <div id="main">
    <div id="userList"></div>
  </div>
</body>
</html>
