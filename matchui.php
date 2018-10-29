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
<link rel="stylesheet" type="text/css" href="detailpopup.css">
<link rel="stylesheet" type="text/css" href="header.css">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<script src="matchui.js" type="text/javascript"></script>
<script src="detailpopup.js" type="text/javascript"></script>
<script src="util.js" type="text/javascript"></script>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
</head>
<body>
  <div id="appHeader">
    <a id="backButton" href="javascript:history.go(-1);"><i class="fas fa-2x fa-angle-left"></i></a>
    <span id="headerText"><span class="firstW">Your </span>Matches</span>
  </div>
  <div id="main">
    <div id="userList"></div>
  </div>
  <div id="detailPopup">
    <div class="appHeader">
      <!-- TODO [Kyle]: use a X button on the right side instead -->
      <a class="backButton" href="javascript:hideDetail();"><i class="fas fa-2x fa-angle-left"></i></a>
      <span class="headerText" id="detailPopupHeaderText"></span>
    </div>
    <div id="detailPopupInner"></div>
  </div>
</body>
</html>
