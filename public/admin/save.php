<?php
ob_start();
session_start();
date_default_timezone_set("Europe/Zurich");


if (isset($_SESSION) && isset($_SESSION['valid']) && $_SESSION['valid'] == true) {
    if ($_SESSION['timeout'] + 30 * 60 < time()) {
        session_destroy();
        if (isset($_GET['ajax'])) {
            $arr = array ('success'=>false, 'error' => 'Please log in');
            echo json_encode($arr);
        } else {
            header("Location: /admin/login.php?error=408");
        }

    } else {
        $_SESSION['timeout'] = time();

        if (isset($_GET['ajax'])) {
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata, true);

            file_put_contents("program.json",  json_encode($request['program'], JSON_PRETTY_PRINT));


            $arr = array ('success'=>true);
            echo json_encode($arr);
        } else {
            if (isset($_POST['save']) &&
                !empty($_POST['date']) &&
                !empty($_POST['description']) &&
                !empty($_POST['details']) &&
                !empty($_POST['tag'])
            ) {
                $file = file_get_contents("program.json");
                $json = json_decode($file, true);

                $rawInput = '{
                   "date": "' . $_POST['date'] . '",
                   "description": "' . $_POST['description'] . '",
                   "details": "' . $_POST['details'] . '",
                   "tag": "' . $_POST['tag'] . '"
                 }';
                $newJson = json_decode($rawInput, true);
                array_push($json, $newJson);
                file_put_contents("program.json", json_encode($json, JSON_PRETTY_PRINT));

                header("Location: /admin/index.php");
            } else {
                header("Location: /admin/index.php");

            }
        }

    }
} else {
    if (isset($_POST['ajax'])) {
        $arr = array ('success'=>false, 'error' => 'Please log in');
        echo json_encode($arr);
    } else {
        header("Location: /admin/login.php");
    }
}
?>