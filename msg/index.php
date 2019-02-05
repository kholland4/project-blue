<?php
require "../util.php";
$conn = create_sql_connection();
$userid = authenticate($conn);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../header.css">
<link rel="stylesheet" type="text/css" href="../app.css">
<link rel="stylesheet" type="text/css" href="index.css">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<script src="../util.js" type="text/javascript"></script>
<script src="index.js" type="text/javascript"></script>
<script type="text/javascript">var userID = <?php echo $userid; ?>;</script>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta charset="UTF-8">
</head>
<body>
<div class="appHeaderCont">
  <div id="appHeader">
    <a id="backButton" href="javascript:history.go(-1);"><i class="fas fa-2x fa-angle-left"></i></a> <!-- TODO: should the back button be a back button or go back to the message thread list? -->
    <span id="headerText">Conversations</span>
  </div>
</div>
<div id="main" style="padding-top: 60px;">
  <div id="messages"></div>
  <div style="display: flex;justify-content: center;"><img id="hDots" src="../img/huugsDots.png"></div>
</div>
</body>
</html>
