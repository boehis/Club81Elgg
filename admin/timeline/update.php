<?php
include '../session.php';
if($auth){
  if (isset($_POST['save']) &&
      isset($_POST['index']) &&
      !empty($_POST['date']) &&
      !empty($_POST['title']) &&
      !empty($_POST['text'])
      )
  {
      $hasError = false;
      $error = "";
      $file = file_get_contents("timeline.json");
      $json = json_decode($file, true);

      $rawInput = '{
        "date": "' . $_POST['date'] . '",
         "title": "' . $_POST['title'] . '",
         "text": "' . $_POST['text'] . '"
       }';
       if(isset($_POST['checkbox'])) {
         $filename = $json[$_POST['index']]["thumbnail"];
         if($filename == ""){
           $name_clean = strtolower(preg_replace('/\s*/', '', $_FILES["thumbnail"]["name"]));
           $date = strtotime($_POST['date']);
           $filename = date('Y-m-d', $date) . $name_clean;
           $json[$_POST['index']]["thumbnail"] = $filename;
         }
         if(file_exists($_FILES["thumbnail"]["tmp_name"])){
           if ($_FILES["thumbnail"]["size"] > 50000) {
             $hasError = true;
             $error = "Thumbnail to large, please minify";
           }else {
             if(file_exists("thumbnails/".$filename)) {
                chmod("thumbnails/".$filename,0755); //Change the file permissions if allowed
                unlink("thumbnails/".$filename); //remove the file
             }
             move_uploaded_file($_FILES["thumbnail"]["tmp_name"], "thumbnails/".$filename);
           }
         }
         $rawInput = '{
           "date": "' . $_POST['date'] . '",
            "title": "' . $_POST['title'] . '",
            "text": "' . $_POST['text'] . '",
            "galeryLink": "' . $_POST['galeryLink'] . '",
            "thumbnail": "'.$json[$_POST['index']]["thumbnail"] . '"
          }';
       }
       if(!$hasError){
         $newJson = json_decode($rawInput, true);
         $json = array_replace($json, array($_POST['index'] => $newJson));
         file_put_contents("timeline.json", json_encode($json, JSON_PRETTY_PRINT));
         header("Location: /admin/timeline/timeline.php?success=true");
       } else {
         header("Location: /admin/timeline/timeline.php?success=false&error=431");
       }
      //echo shell_exec("sh ../../backup-program.sh");
      } else {
        header("Location: /admin/timeline/timeline.php?success=false&error=400");
      }
  }
?>
