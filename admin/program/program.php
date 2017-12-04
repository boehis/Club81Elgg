<html lang="en">
<head>
  <?php
  include '../session.php';
  include '../includes.php';
  ?>
  <script src="app.min.js"></script>
</head>
<body ng-app="admin" ng-controller="controller" class="container">
<?php
include '../nav.php';?>

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
