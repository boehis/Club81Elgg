<?php
ob_start();
session_start();
date_default_timezone_set("Europe/Zurich");
$auth = false;
if (isset($_SESSION) && isset($_SESSION['valid']) && $_SESSION['valid'] == true) {
    if ($_SESSION['timeout'] + 30 * 60 < time()) {
        session_destroy();
        if (isset($_GET['ajax'])) {
            $arr = array ('success'=>false, 'error' => 'Please log in');
            echo json_encode($arr);
        } else {
            header("Location: /admin/login/login.php?error=408");
        }
    } else {
        $_SESSION['timeout'] = time();
        $auth = true;
    }
} else {
  if (isset($_POST['ajax'])) {
      $arr = array ('success'=>false, 'error' => 'Please log in');
      echo json_encode($arr);
  } else {
      header("Location: /admin/login/login.php");
  }
}
?>
