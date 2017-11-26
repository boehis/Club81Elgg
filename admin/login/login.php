<?php
  session_start();
  if(isset($_SESSION['valid'])){
    $_SESSION['valid'] == false;
  }

  if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {

  $config = json_decode(file_get_contents("../../config.json"), true);
  $db = $config["db"];
  // Create connection
  $conn = new mysqli($db["url"], $db["username"], $db["password"]);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  echo "Connected successfully ";

  $stmt = $conn->prepare('SELECT * FROM `club81`.`user` WHERE uname = ? AND passwd = ?');
  $stmt->bind_param('ss', $_POST['username'], hash('sha512', $_POST['password']));

  $stmt->execute();

  $result = get_result($stmt);

  if(count($result) != 1){
      session_destroy();
      sleep(1);
      if(count($result) == 0){
        header("Location: /admin/login/login.php?error=401");
      }else {
        header("Location: /admin/login/login.php?error=409");
      }

  } else {
    $_SESSION['valid'] = true;
    $_SESSION['timeout'] = time();
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['role'] = $result[0]["role"];

    echo 'You have entered valid use name and password';
    header("Location: /admin/timeline/timeline.php");
  }

}


function get_result( $Statement ) {
    $RESULT = array();
    $Statement->store_result();
    for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
        $Metadata = $Statement->result_metadata();
        $PARAMS = array();
        while ( $Field = $Metadata->fetch_field() ) {
            $PARAMS[] = &$RESULT[ $i ][ $Field->name ];
        }
        call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
        $Statement->fetch();
    }
    return $RESULT;
}
?>

<html lang = "en">
   <head>
     <?php
     include '../includes.php';
     ?>
     <link rel="stylesheet" type="text/css" href="login.min.css"/>
   </head>
   <body>
     <div class = "container">
         <div class="wrapper">
             <form class="form-signin" role = "form" action = "login.php" method = "post">
                 <h3 class="form-signin-heading">Welcome Back! Please Sign In</h3>
                 <hr class="colorgraph"><br>
                 <?php
                 if (isset($_GET['error'])) {
                   echo '<div class="alert alert-danger">';
                   switch ($_GET['error']) {
                        case "401":
                            echo "Wrong Username and Password";
                            break;
                        case "408":
                            echo "Request Timeout. Log in again.";
                            break;
                        case "409":
                            echo "Request Timeout. Log in again.";
                            break;
                        default:
                            echo "Error: " . $_GET['error'] ;
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
