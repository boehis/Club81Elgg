<?php
ob_start();
session_start();
date_default_timezone_set("Europe/Zurich");
?>
<html lang="en">
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
    <link rel="stylesheet" type="text/css" href="index.css"/>

    <!-- Favicon
 –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" href="../images/favicon.png">

    <!-- Scripts
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
    <script src="app.js"></script>
</head>
<body ng-app="admin" ng-controller="controller" class="container">
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
} else {
    header("Location: /admin/login.php");
}
?>
<form role="form" action="logout.php" method="post">
    <button class="btn btn-default" type="submit" name="logout">Logout</button>
</form>
<a href="/">Home</a>

<hr>

<h2>Add Event</h2>
<form role="form" action="save.php" method="post" class="form-horizontal">
    <div class="form-group">
        <label class="control-label col-sm-2" for="date">Date:</label>
        <div class="col-sm-10">
            <input class="form-control" id="date" type="date" name="date" value="<?php echo date("Y-m-d") ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="description">Description:</label>
        <div class="col-sm-10">
            <input class="form-control" id="description" type="text" name="description" placeholder="description"
                   required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="details">Details:</label>
        <div class="col-sm-10">
            <input class="form-control" id="details" type="text" name="details" placeholder="details" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="tag">Tag:</label>
        <div class="col-sm-10">
            <select class="form-control" id="tag" name="tag" required>
                <option value="none">None</option>
                <option value="highlight">Highlight</option>
                <option value="compulsory">Compulsory</option>
                <option value="public">Public</option>
                <option value="private">Private</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="save" class="btn btn-default">Save</button>
        </div>
    </div>
</form>

<hr>

<h2>Edit Events</h2>
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>Datum</th>
            <th>Beschreibung</th>
            <th>Details</th>
            <th>Tag</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <tr ng:repeat="p in program" class={{colors[p.tag]}}>
            <td><input class="form-control" ng-model="p.date" type="date" name="date"></td>
            <td><input class="form-control" ng-model="p.description" type="text" name="description">
            </td>
            <td><input class="form-control" ng-model="p.details" type="text" name="details"></td>
            <td>
                <select class="form-control" ng-model="p.tag" name="tag" required>
                    <option value="none">None</option>
                    <option value="highlight">Highlight</option>
                    <option value="compulsory">Compulsory</option>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </td>
            <td>
                <button class="btn btn-danger" type="submit" name="update" value="delete" ng-click="program.splice($index, 1)">Delete</button>
            </td>
        </tr>
        </tbody>
    </table>
    <button class="btn btn-default" type="submit" ng-click="save()">Save</button>
</div>


<p ng-class="{'alert-danger' : error, 'alert-success' : !error}">{{message}}</p>

<hr>

</body>
</html>
