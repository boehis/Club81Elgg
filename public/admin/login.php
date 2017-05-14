<?php
  session_start();
  if(isset($_SESSION['valid'])){
    $_SESSION['valid'] == false;
  }
?>
<html lang = "en">

   <head>

   </head>

   <body>
     <?php
      if (isset($_GET['error'])) {
        if($_GET['error'] == "401") {
          echo "Wrong Username and Password";
        } else if($_GET['error'] == "408") {
          echo "Request Timeout. Log in again.";
        }
      }
      ?>
         <form role = "form" action = "index.php" method = "post">
            <input type = "text" name = "username" placeholder = "username" required autofocus></br>
            <input type = "password" name = "password" placeholder = "password " required>
            <button type = "submit" name = "login">Login</button>
         </form>

   </body>
</html>
