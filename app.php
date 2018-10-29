<?php
require "util.php";
?>
<!DOCTYPE html>
<html> 
<head>
<title><?php echo $DISPLAY_NAME; ?></title> 
<link rel="stylesheet" type="text/css" href="app.css">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
</head>
<body>
  <div class="topPart">
    <div class="menuContainer" onclick="toggleMenu(this)">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div> 
    </div>
    <div class="dropdown-content" id="myDropdown">
      <?php 
      echo "<a href='" . $BASE_URL . "userlist.php'>The List</a>";
      echo "<a href='#'>Chatting</a>";
      echo "<a href='#'>Forums</a>";
      ?>
    </div>
  </div>
  <div class="main">
      <?php
      $conn = create_sql_connection();
      //TODO: just use global $conn
      $userid = get_login_user($conn);
      if($userid !== null) {
        $name = get_user_name($conn, $userid);
        echo "<span>Hello, $name!</span><br>\n";
        echo "<a href='" . $BASE_URL . "prefsui.php'>Your interests</a><br>\n";
        echo "<a href='" . $BASE_URL . "matchui.php'>People like you</a><br>\n";
        echo "<a href='" . $BASE_URL . "logout.php'>Log out</a><br>";
        echo "<a href='" . $BASE_URL . "profileui.php'>Your Profile</a>";
      }
      close_sql_connection($conn);
      ?>    
  </div>
  <script type="text/javascript">
  function toggleMenu(x){x.classList.toggle("change"); myFunction()}

  function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

/*  window.onclick = function(e) {
    if (!e.target.matches('.menuContainer')) {
      var myDropdown = document.getElementById("myDropdown");
        if (myDropdown.classList.contains('show')) {
          myDropdown.classList.remove('show');
        }
    }
  }*/
</script>
</body>
</html>
