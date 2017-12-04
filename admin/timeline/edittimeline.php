<html lang="en">
<head>
  <?php
  include '../session.php';
  include '../includes.php';
  $mode = (isset($_GET['mode']) && $_GET['mode'] == 'edit') ? 1 : 0;
  $index = (isset($_GET['index'])) ? $_GET['index'] : -1;
  ?>
  <script>var index = <?=$index?></script>
  <script src="timeline.min.js"></script>
</head>
<body ng-app="timeline" ng-controller="controller" class="container">
  <h2><?= $mode ?'Edit':'Add'?> Timeline Entry</h2>

  <form role="form" action="update.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    <input type="hidden" name="index" value="<?=$index?>"/>
    <div class="form-group">
        <label class="control-label col-sm-2" for="date">Date</label>
        <div class="col-sm-10">
            <input class="form-control" id="date" type="date" name="date" ng-model="event.date" required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="title">Title</label>
        <div class="col-sm-10">
            <input class="form-control" id="title" type="text" name="title" ng-model="event.title" placeholder="Titel"
                   required>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2" for="text">Text</label>
        <div class="col-sm-10">
            <textarea class="form-control" id="text" name="text" ng-model="event.text" rows="4"></textarea>
        </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="checkbox[]" id="checkbox" value="galery" ng-model="event.hasGalery"> Bilder
          </label>
        </div>
      </div>
    </div>
    <div class="form-group" ng-show="event.hasGalery">
      <label class="control-label col-sm-2" for="thumbnail">Thumbnail</label>
      <div class="col-sm-10">
        <input type="file" id="thumbnail" name="thumbnail">
        <p class="help-block">Dieses Bild wird auf der Webseite angezeigt. Nur komprimierte Bilder hochladen.</p>
        <img class="img-responsive hidden-xs" ng-src="{{event.thumbnail}}"/>
      </div>
    </div>
    <div class="form-group" ng-show="event.hasGalery">
        <label class="control-label col-sm-2" for="galeryLink">Galery Link</label>
        <div class="col-sm-10">
            <input class="form-control" id="galeryLink" type="url" name="galeryLink" ng-model="event.galeryLink" placeholder="http://galerie.com">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="save" class="btn btn-default">Save</button>
        </div>
    </div>
  </form>
</body>
</html>
