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
<link rel="stylesheet" type="text/css" href="header.css">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<script src="userlist.js" type="text/javascript"></script>
<script src="detailpopup.js" type="text/javascript"></script>
<script src="util.js" type="text/javascript"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
</head>
<body>
  <div id="appHeader">
    <a id="backButton" href="javascript:history.go(-1);"><i class="fas fa-2x fa-angle-left"></i></a>
    <span id="headerText"><span id="firstW">All </span> Users</span>
  </div>
  <div id="main">
    <div id="userList"></div>
  </div>
  <div id="detailPopup">
    <div class="appHeader">
     <a id="backButton" href="javascript:hideDetail();"><i class="far fa-2x fa-times-circle"></i></a>
    </div>
    <div id="detailPopupInner"></div>
  </div>
  <div style="display: flex;justify-content: center;"><img id="hDots" src="img/huugsDots.png"></div>
</body>
</html>
