<?php
include '../session.php';
if($auth){
  if (isset($_GET['index']))
  {
      $file = file_get_contents("timeline.json");
      $json = json_decode($file, true);
      $filename = $json[$_GET['index']]["thumbnail"];
      if($filename != "" && file_exists("thumbnails/". $filename)) {
         chmod("thumbnails/".$filename,0755); //Change the file permissions if allowed
         unlink("thumbnails/".$filename); //remove the file
      }
      unset($json[$_GET['index']]);
      $json = array_values($json);
      file_put_contents("timeline.json", json_encode($json, JSON_PRETTY_PRINT));
      header("Location: /admin/timeline/timeline.php?success=true");
  }
}
?>
