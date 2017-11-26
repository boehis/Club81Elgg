<html lang="en">
<head>
  <?php
  include '../session.php';
  include '../includes.php';
  ?>
  <script src="timeline.min.js"></script>
</head>
<body ng-app="timeline" ng-controller="controller" class="container">


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

 include '../nav.php';
 ?>

  <h2><a href="edittimeline.php">Add Event</a></h2>
  <hr>

  <h2>Edit Timeline</h2>
  <div ng-show="error" class="alert alert-danger">
    <strong>Error!</strong> Die Events konnten nicht geladen werden.
  </div>
  <div class="table-responsive">
      <table class="table">
          <thead>
          <tr>
              <th>Datum</th>
              <th>Titel</th>
              <th>Edit</th>
              <th>Delete</th>
          </tr>
          </thead>
          <tbody>
          <tr ng:repeat="e in events">
              <td>{{e.date | date}}</td>
              <td>{{e.title}}</td>
              <td>
                  <button class="btn btn-default" type="submit" name="update" value="edit" ng-click="edit($index)">Edit</button>
              </td>
              <td>
                  <button class="btn btn-danger" type="submit" name="update" value="delete" ng-click="delete($index)">Delete</button>
              </td>
          </tr>
          </tbody>
      </table>
  </div>



</body>
</html>
