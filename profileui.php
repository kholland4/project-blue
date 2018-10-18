<?php 
require "util.php";
$conn = create_sql_connection();
$userid = authenticate($conn);
?>
<DOCTYPE! html>
<html>
<head>
  <title>Profile - <?php echo $DISPLAY_NAME; ?></title>
  <link rel="stylesheet" type="text/css" href="profileui.css">
  <link rel="stylesheet" type="text/css" href="header.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="mobile-web-app-capable" content="yes">
</head>
<body>
  <div id="container">
  	<div id=appHeader>
      <a id="backButton" href="javascript:history.go(-1)">
      	<i class="fas fa-2x fa-angle-left"></i>
      </a>
      <span id="headerText"><span class="firstW">Your </span>Profile</span>
    </div>
    <div class="userCont">
      <div class="userInfo">
        <img class="detailIcon" src="img/pp.png">
      </div>
      <p id="usersName">[PlaceHolder Text]</p>
    </div>
  </div>
</body>
</html>