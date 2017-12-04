<?php
include '../session.php';
if($auth){
        if (isset($_GET['ajax'])) {
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata, true);

            file_put_contents("program.json",  json_encode($request['program'], JSON_PRETTY_PRINT));
			      echo shell_exec("sh ../../../backup-program.sh");

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
				        echo shell_exec("sh ../../../backup-program.sh");

            }
            header("Location: program.php?");
        }

    }

?>
