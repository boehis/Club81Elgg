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
            //update all edited editedTexts
            foreach ($request['editedTexts'] as $event) {
              $textFile = fopen("timeline/" . $event["textUrl"], "w");
              fwrite($textFile, $event["text"]);
              fclose($textFile);
            }
            //save json
            file_put_contents("timeline.json",  json_encode($request['timeline'], JSON_PRETTY_PRINT));
            //echo shell_exec("sh ../../backup-program.sh");

            $arr = array ('success'=>true);
            //echo json_encode($arr);
        } else {
            if (isset($_POST['save']) &&
                !empty($_POST['date']) &&
                !empty($_POST['title']) &&
                !empty($_POST['text']) &&
                isset($_POST['galeryLink'])
            ) {
              $hasError = false;
              $error = "";

                $file = file_get_contents("timeline.json");
                $json = json_decode($file, true);

                $name_clean = preg_replace('/\s*/', '', $_POST['title']);
                $name_clean = strtolower($name_clean);
                $date = strtotime($_POST['date']);
                $filename = $name_clean . date('Y-m-d', $date);
                $textFile = fopen("timeline/" . $filename. ".htm", "w");
                fwrite($textFile, $_POST['text']);
                fclose($textFile);

                $rawInput = '{
                  "date": "' . $_POST['date'] . '",
                   "title": "' . $_POST['title'] . '",
                   "text": "' . $filename . '.htm"
                 }';
                 if(!empty($_POST['galeryLink'])) {

                   $target_file = "timeline/" . $filename . $_FILES["thumbnail"]["name"];
                   if ($_FILES["thumbnail"]["size"] > 50000) {
                     $hasError = true;
                     $error = "Thumbnail to large, please minify";
                   } else {
                     move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file);

                     $rawInput = '{
                       "date": "' . $_POST['date'] . '",
                        "title": "' . $_POST['title'] . '",
                        "text": "' . $filename . '.htm",
                        "galeryLink": "' . $_POST['galeryLink'] . '",
                        "thumbnail": "/admin/timeline/' . $filename . $_FILES["thumbnail"]["name"] . '"
                      }';
                   }
                 }
                 if(!$hasError){
                   $newJson = json_decode($rawInput, true);
                   array_push($json, $newJson);
                   file_put_contents("timeline.json", json_encode($json, JSON_PRETTY_PRINT));
                   header("Location: /admin/timeline.php?success=true");
                 } else {
                   header("Location: /admin/timeline.php?success=false&filesize");
                 }
                //echo shell_exec("sh ../../backup-program.sh");
            } else {
                header("Location: /admin/timeline.php?success=false&missingFields");
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
