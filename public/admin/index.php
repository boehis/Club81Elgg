<?php
ob_start();
session_start();
date_default_timezone_set("Europe/Zurich");
?>
<html lang="en">
<head>
</head>
<?php
if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {

    $pw_hash = "2bbe0c48b91a7d1b8a6753a8b9cbe1db16b84379f3f91fe115621284df7a48f1cd71e9beb90ea614c7bd924250aa9e446a866725e685a65df5d139a5cd180dc9";
    if ($_POST['username'] == 'boehis' && hash('sha512', $_POST['password']) == $pw_hash) {
        $_SESSION['valid'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['username'] = 'boehis';

        echo 'You have entered valid use name and password';
    } else {
        session_destroy();
        sleep(1);
        header("Location: /admin/login.php?error=401");
    }

} else if (isset($_SESSION) && isset($_SESSION['valid']) && $_SESSION['valid'] == true) {
    if ($_SESSION['timeout'] + 30 * 60 < time()) {
        session_destroy();
        header("Location: /admin/login.php?error=408");
    } else {
        $_SESSION['timeout'] = time();
    }
    echo "loged in";
    if (isset($_POST['savefile']) && !empty($_POST['program'])) {
        file_put_contents("program.json", $_POST['program']);
        echo "saved";
    } else if (isset($_POST['save']) &&
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

        echo "saved";
    }
} else {
    header("Location: /admin/login.php");
}
?>
<form role="form" action="logout.php" method="post">
    <button type="submit" name="logout">Logout</button>
</form>
<form role="form" action="" method="post">
    <input type="date" name="date" value="<?php echo date("Y-m-d") ?>" required></br>
    <input type="text" name="description" placeholder="description" required></br>
    <input type="text" name="details" placeholder="details" required></br>
    <select name="tag" required>
        <option value="none">None</option>
        <option value="highlight">Highlight</option>
        <option value="compulsory">Compulsory</option>
        <option value="public">Public</option>
        <option value="private">Private</option>
    </select>
    <button type="submit" name="save">Save</button>
</form>
<form role="form" action="" method="post" onsubmit="return parseJSON()">
    <textarea id="program" name="program"
              style="margin: 0px; width: 80%; height: 300px;"><?php echo file_get_contents("program.json") ?></textarea>
    <button type="submit" name="savefile">Save</button>
</form>
<p id="parseError">
</p>

<a href="/">Home</a>

<script>
    function parseJSON() {
        var text = document.getElementById('program').value;
        try {
            JSON.parse(text);
        } catch (e) {
            document.getElementById("parseError").innerHTML = e;
            return false;
        }
        return true;
    }

</script>
</body>
</html>
