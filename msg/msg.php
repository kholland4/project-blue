<?php
require "../util.php";
$conn = create_sql_connection();
$userid = authenticate($conn);

if(!array_key_exists("target", $_GET)) {
  header("Location: index.php");
  exit();
}
if(!is_numeric($_GET["target"])) {
  header("Location: index.php");
  exit();
}

$target_userid = intval($_GET["target"]);
$target_user = get_user_info($conn, $target_userid);

$target_name = get_user_name($conn, $target_userid); //FIXME [Kyle]: !!!!! FIX THIS !!!!!

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../header.css">
<link rel="stylesheet" type="text/css" href="../app.css">
<link rel="stylesheet" type="text/css" href="msg.css">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<script src="../util.js" type="text/javascript"></script>
<script src="msg.js" type="text/javascript"></script>
<script type="text/javascript">var userID = <?php echo $userid; ?>; var targetUserID = <?php echo $target_userid; ?>;</script>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta charset="UTF-8">
</head>
<body>
<div id="appHeaderCont">
  <div id="appHeader">
    <a id="backButton" href="javascript:history.go(-1);"><i class="fas fa-2x fa-angle-left"></i></a> <!-- TODO: should the back button be a back button or go back to the message thread list? -->
    <img class="userBubbleIcon" src="../img/pp.png">
    <span id="headerText"><?php echo htmlspecialchars($target_user['firstname']." ". $target_user['lastname']); ?></span>
  </div>
</div>
<div id="main" style="padding-top: 60px;">
  <div id="messages"></div>
  <div style="display: flex;justify-content: center;"><img id="hDots" src="../img/huugsDots.png"></div>
</div>
<div id="sendMessage">
    <form id="sendMessageForm" action="javascript:;" onsubmit="sendMessage();"> 
      <textarea 
        type="text" 
        id="sendMessageText" 
        onkeydown="textAreaAdjust();"></textarea>
      <button id="sendButton"><i id="sendIcon" class="fas fa-paper-plane"></i></button>
    </form>
</div>
</body>
</html>
