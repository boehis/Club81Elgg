"use strict";

(function() {

  angular.module("timeline", [])
    .controller('controller', ['$scope', '$http', function($scope, $http) {
      var eventsCopy = []
      $http({
        method: 'GET',
        url: 'timeline.json'
      }).then(function successCallback(response) {
        $scope.events = []

        for (var i = 0; i < response.data.length; i++) {
          var data = response.data[i]
          var event = {}
          event.date = new Date(data.date)
          event.title = data.title
          event.text = data.text
          event.hasGalery = data.galeryLink || data.thumbnail ? true : false
          event.galeryLink = data.galeryLink
          event.thumbnail = "thumbnails/"+data.thumbnail

          $scope.events.push(event)
        }
        if(index != -1){
          $scope.event = $scope.events[index]
        }else{
          $scope.event = {}
          $scope.event.date = new Date()
          $scope.event.title = ""
          $scope.event.text = ""
          $scope.event.hasGalery = true
          $scope.event.galeryLink = ""
          $scope.event.thumbnail = ""
        }
      }, function errorCallback(response) {
        $scope.error = true;
      });


      $scope.edit = function(i) {
        window.location.href = 'edittimeline.php?mode=edit&index=' + i
      }
      $scope.delete = function(i) {
        window.location.href = 'delete.php?index=' + i
      }
    }]);
})();
