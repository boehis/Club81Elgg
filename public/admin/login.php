<?php
  session_start();
  if(isset($_SESSION['valid'])){
    $_SESSION['valid'] == false;
  }
?>
<html lang = "en">
   <head>

       <!-- Basic Page Needs
–––––––––––––––––––––––––––––––––––––––––––––––––– -->
       <meta charset="UTF-8">
       <title>Club 81</title>
       <meta name="author" content="Simon Böhi">
       <meta name="robots" content="noindex">

       <!-- Setup Metas
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
       <meta name="viewport" content="width=device-width, initial-scale=1">

       <!-- CSS
–––––––––––––––––––––––––––––––––––––––––––––––––– -->
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.css">
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
             integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
             integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
       <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200i,300">
       <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
       <link rel="stylesheet" type="text/css" href="login.css"/>

       <!-- Favicon
–––––––––––––––––––––––––––––––––––––––––––––––––– -->
       <link rel="icon" href="images/favicon.png">

   </head>

   <body>

	 <!--Inspired by https://bootsnipp.com/snippets/featured/simple-login-form-bootsnipp-style-colorgraph -->  
     <div class = "container">
         <div class="wrapper">
             <form class="form-signin" role = "form" action = "index.php" method = "post">
                 <h3 class="form-signin-heading">Welcome Back! Please Sign In</h3>
                 <hr class="colorgraph"><br>
                 <?php
                 if (isset($_GET['error'])) {
                     echo '<div class="alert alert-danger">';
                     if($_GET['error'] == "401") {
                         echo "Wrong Username and Password";
                     } else if($_GET['error'] == "408") {
                         echo "Request Timeout. Log in again.";
                     }
                     echo '</div>';
                 }
                 ?>
                 <input type="text" class="form-control" name = "username" placeholder = "username" required autofocus />
                 <input type="password" class="form-control"  name = "password" placeholder = "password " required/>

                 <button class="btn btn-lg btn-primary btn-block"  name="login" type="submit">Login</button>
             </form>
         </div>
     </div>
   </body>
</html>
