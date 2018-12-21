<?php 
require "util.php";
$conn = create_sql_connection();
$userid = authenticate($conn);
$userInfo = get_user_info($conn, $userid);
?>
<DOCTYPE! html>
<html>
<head>
  <title>Profile - <?php echo $DISPLAY_NAME; ?></title>
  <link rel="stylesheet" type="text/css" href="prefsui.css">
  <link rel="stylesheet" type="text/css" href="profileui.css">
  <?php import("includes.html"); ?>
</head>
<body>
  <div id="container">
    <div class="appHeaderCont">
      <div id="appHeader">
        <a id="backButton" href="javascript:history.go(-1)">
          <i class="fas fa-2x fa-angle-left"></i>
        </a>
        <span id="headerText"><span class="firstW">Your </span>Profile</span>
      </div>      
    </div>
    <div class="userCont">
      <div class="userPic">
        <img class="detailIcon" src="img/pp.png">
      </div>
      <div class="userInfo">
        <p id="usersName">
        <?php 
          echo $userInfo["firstname"]." ".$userInfo["lastname"];
        ?></p>
        <p id="username">
          <?php 
            echo $userInfo["username"];
          ?>
        </p>
        <p class="userBio">Insert the clip art, resize it, change text wrapping to In Front of Text, move it to the left on the shape, and format it as indicated in the figure. Copy the clip art and move the copy of the image to the right on the shape, as shown in the figure.
</p>
        <i class="fas fa-graduation-cap">: <span style="color: black; font-weight: normal;">[school]</span></i>
        <div class="imageCarousel">
          <img class="carouselPic" src="https://images.unsplash.com/photo-1526749837599-b4eba9fd855e?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=36f243a4902af3e45f4f2f3c031f04f6&auto=format&fit=crop&w=1500&q=80">
          <img class="carouselPic" src="https://images.unsplash.com/photo-1526749837599-b4eba9fd855e?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=36f243a4902af3e45f4f2f3c031f04f6&auto=format&fit=crop&w=1500&q=80">
          <img class="carouselPic" src="https://images.unsplash.com/photo-1526749837599-b4eba9fd855e?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=36f243a4902af3e45f4f2f3c031f04f6&auto=format&fit=crop&w=1500&q=80">
          <img class="carouselPic" src="https://images.unsplash.com/photo-1526749837599-b4eba9fd855e?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=36f243a4902af3e45f4f2f3c031f04f6&auto=format&fit=crop&w=1500&q=80">
        </div>
        <button class="editButt">Edit Profile</button>
      </div>
      <div class="interestCont">
        <p>Your Interests</p>
        <div class="interestList"></div>
        <a href="prefsui.php">interest</a><br>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    //stay in webapp mode
    var a = document.getElementsByTagName("a");
    for(var i = 0; i < a.length; i++) {
      a[i].onclick=function() {
        window.location = this.getAttribute("href");
        return false
      }
    }
  </script>
</body>
</html>
