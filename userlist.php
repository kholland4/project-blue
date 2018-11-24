<?php
require "util.php";
$conn = create_sql_connection();
$userid = authenticate($conn);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="userlist.css">
<link rel="stylesheet" type="text/css" href="detailpopup.css">
<script src="userlist.js" type="text/javascript"></script>
<script src="detailpopup.js" type="text/javascript"></script>
<script src="util.js" type="text/javascript"></script>
<?php import("includes.html"); ?>
</head>
<body>
<div id="topPart">
  <div id="appHeader">
    <a id="backButton" href="javascript:history.go(-1);"><i class="fas fa-2x fa-angle-left"></i></a>
    <span id="headerText"><span id="firstW">All </span> Users</span>
  </div>
  <div class="search" style="margin-top: 6px;"><input class= "searchbar" type="search" name="searchUsers" placeholder="Find Users"></div>
</div>
<div id="main">
  <div id="userList"></div>
  <div style="display: flex;justify-content: center;"><img id="hDots" src="img/huugsDots.png"></div>
</div>
<?php import("detailPopup.html"); ?>
</body>
</html>
