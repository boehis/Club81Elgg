<?php
include '../session.php';
if ($auth) {
    if (isset($_POST['save']) &&
    !empty($_POST['date']) &&
    !empty($_POST['title']) &&
    !empty($_POST['text'])) {
        $editMode = isset($_POST['index']) && $_POST['index'] != "-1";
        $hasError = false;
        $error    = "";
        $file     = file_get_contents("timeline.json");
        $json     = json_decode($file, true);

        $rawInput = [];
        $rawInput["date"] = normalize($_POST['date']);
        $rawInput["title"] = normalize($_POST['title']);
        $rawInput["text"] = normalize($_POST['text']);

        if(!empty($_POST['galeryLink'])){
          $rawInput["galeryLink"] = normalize($_POST['galeryLink']);
        }
        if (isset($_POST['checkbox']) &&
        (!empty($_FILES["thumbnail"]["name"]) || $editMode)) {
            $filename = "";
            if(!$editMode || !isset($json[$_POST['index']]["thumbnail"]) || $json[$_POST['index']]["thumbnail"] == ""){
              $name_clean = strtolower(preg_replace('/\s*/', '', $_FILES["thumbnail"]["name"]));
              $date = strtotime($_POST['date']);
              $filename =  date('Y-m-d', $date) . $name_clean;
            } else {
              $filename = $json[$_POST['index']]["thumbnail"];
            }
            if (file_exists($_FILES["thumbnail"]["tmp_name"])) {
                if ($_FILES["thumbnail"]["size"] > 50000) {
                    $hasError = true;
                    $error    = "431";
                } else {
                    if (file_exists("thumbnails/" . $filename)) {
                        chmod("thumbnails/" . $filename, 0755); //Change the file permissions if allowed
                        unlink("thumbnails/" . $filename); //remove the file
                    }
                    move_uploaded_file($_FILES["thumbnail"]["tmp_name"], "thumbnails/" . $filename);
                }
            }
            $rawInput["thumbnail"] = normalize($filename);
        }
        if (!$hasError) {
            if($json == null && $rawInput == null){
              $json = array();
            }else if($json == null) {
              $json = array($rawInput);
            }else if($rawInput != null) {
              if($editMode){
                $json = array_replace($json, array(
                  $_POST['index'] => $rawInput
                ));
              }else {
                array_push($json, $rawInput);
              }
            }
            file_put_contents("timeline.json", json_encode($json, JSON_PRETTY_PRINT));
            header("Location: /admin/timeline/timeline.php?success=true");
        } else {
            header("Location: /admin/timeline/timeline.php?success=false&error=" . $error);
        }
        //echo shell_exec("sh ../../backup-program.sh");
    } else {
        header("Location: /admin/timeline/timeline.php?success=false&error=400");
    }
    echo "Error";
}

function normalize($string){
    return str_replace('"', '\"', $string);
}

?>
