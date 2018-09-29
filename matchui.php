<?php
require "util.php";
$conn = create_sql_connection();
$userid = authenticate($conn);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="matchui.css">
<script src="matchui.js" type="text/javascript"></script>
</head>
<body>
  <a id="back" href="<?php echo $BASE_URL; ?>"><i class="fas fa-2x fa-angle-left"></i></a>
</body>
</html>
