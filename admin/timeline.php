<?php
ob_start();
session_start();
date_default_timezone_set("Europe/Zurich");

if (isset($_SESSION) && isset($_SESSION['valid']) && $_SESSION['valid'] == true) {
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
    <link rel="stylesheet" type="text/css" href="index.min.css"/>

    <!-- Favicon
 –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" href="../images/favicon.png">

    <!-- Scripts
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <script>
      class AsyncTaskCounter {
        constructor(callback) {
          this.callback = callback
          this.counter = 0
        }
        incr() {
          this.counter ++
        }
        decr() {
          this.counter --
          if(this.counter == 0){
            this.callback()
          }
        }
      }
    </script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
    <script src="timeline.min.js"></script>
</head>
<body ng-app="timeline" ng-controller="controller" class="container">
  <ul>
    <li><a href="/">Home</a></li>
    <li><a href="index.php">Edit Events</a></li>
  </ul>

  <hr>

  <h2>Add Timeline Entry</h2>
<?php
 if(isset($_GET['success'])) {
   if($_GET['success']=="true") {?>
     <div class="alert alert-success">
       <strong>Success!</strong> Die Daten wurden gespeichert.
     </div>
   <?php
 } else {?>
       <div class="alert alert-danger">
         <strong>Error!</strong> Die Daten konnten nicht gespeichert werden.
       </div>
     <?php
   }
 }
 ?>

  <form role="form" action="saveTimeline.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class="form-group">
        <label class="control-label col-sm-2" for="date">Date</label>
        <div class="col-sm-10">
            <input class="form-control" id="date" type="date" name="date" value="<?php echo date("Y-m-d") ?>" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="title">Title</label>
        <div class="col-sm-10">
            <input class="form-control" id="title" type="text" name="title" placeholder="Titel"
                   required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="text">Text</label>
        <div class="col-sm-10">
            <textarea class="form-control" id="text" name="text" rows="4"></textarea>
        </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
          <label>
            <input type="checkbox" ng-model="hasImages"> Bilder
          </label>
        </div>
      </div>
    </div>
    <div class="form-group" ng-show="hasImages">
      <label class="control-label col-sm-2" for="thumbnail">Thumbnail</label>
      <div class="col-sm-10">
        <input type="file" id="thumbnail" name="thumbnail">
        <p class="help-block">Dieses Bild wird auf der Webseite angezeigt. Nur komprimierte Bilder hochladen.</p>
      </div>
    </div>
    <div class="form-group" ng-show="hasImages">
        <label class="control-label col-sm-2" for="galeryLink">Galery Link</label>
        <div class="col-sm-10">
            <input class="form-control" id="galeryLink" type="url" name="galeryLink" placeholder="http://galerie.com">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="save" class="btn btn-default">Save</button>
        </div>
    </div>
  </form>

  <hr>

  <h2>Edit Timeline</h2>

  <div ng-show="error" class="alert alert-danger">
    <strong>Error!</strong> Die Events konnten nicht geladen werden. 
  </div>
  <div ng:repeat="event in events" class="col-xs-12">
    <div class="card">
      <div class="row">
        <div class="col-xs-3 form-group">
          <label class="control-label" for="date{{$index}}">Date</label>
          <input class="form-control" id="date{{$index}}" type="date" ng-model="event.date">
        </div>
        <div class="col-xs-9 form-group">
          <label class="control-label" for="title{{$index}}">Title</label>
          <input class="form-control" id="title{{$index}}" type="text" ng-model="event.title">
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-7 form-group">
          <label class="control-label" for="text{{$index}}">Text</label>
          <textarea class="form-control" id="title{{$index}}" ng-model="event.text" rows="4"></textarea>
        </div>
        <div class="col-xs-12 col-sm-5">
          <div class="checkbox">
            <label>
              <input type="checkbox" ng-model="event.hasGalery"> Bilder
            </label>
          </div>
          <div class="form-group" ng-show="event.hasGalery">
            <label class="control-label col-sm-2" for="thumbnail{{$index}}">Thumbnail</label>
            <div class="col-sm-10">
              <input type="file" id="thumbnail{{$index}}" ng-model="event.thumbnail">
              <p class="help-block">Dieses Bild wird auf der Webseite angezeigt. Nur komprimierte Bilder hochladen.</p>
              <img class="img-responsive" ng-src="{{event.thumbnail}}"/>
            </div>
          </div>
          <div class="form-group" ng-show="event.hasGalery">
              <label class="control-label col-sm-2" for="galeryLink{{$index}}">Galery Link</label>
              <div class="col-sm-10">
                  <input class="form-control" id="galeryLink{{$index}}" type="url" ng-model="event.galeryLink">
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <button class="btn btn-default" type="submit" ng-click="saveEvents()">Save</button>
  <p ng-class="{'alert-danger' : saveError, 'alert-success' : !saveError}">{{message}}</p>

</body>
</html>
