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

        var asyncTaskCounter = new AsyncTaskCounter(function() {
          eventsCopy = JSON.parse(JSON.stringify($scope.events))
        })

        for (var i = 0; i < response.data.length; i++) {
          var data = response.data[i]
          var event = {}
          event.date = new Date(data.date)
          event.title = data.title
          event.text = "Loading..."
          event.textUrl = data.text
          event.hasGalery = !(data.galeryLink == "" || data.thumbnail == "")
          event.galeryLink = data.galeryLink
          event.thumbnail = data.thumbnail

          function loadText(event) {
            $http({
              method: 'GET',
              url: '../admin/timeline/' + data.text
            }).then(function successCallback(response) {
              event.text = response.data
              asyncTaskCounter.decr()
            }, function errorCallback(response) {
              event.text = response.statusText
              asyncTaskCounter.decr()
            });
          }

          asyncTaskCounter.incr()
          loadText(event)

          $scope.events.push(event)
        }
      }, function errorCallback(response) {
        $scope.error = true;
      });

      $scope.saveEvents = function() {
        var editedTexts = []
        var events = JSON.parse(JSON.stringify($scope.events));

        for (var i = 0; i < events.length; i++) {
          var event = events[i]
          if (event.text != eventsCopy[i].text) {
            editedTexts.push(JSON.parse(JSON.stringify(event)))
          }
          if (!event.hasGalery) {
            event.thumbnail = ""
            event.galeryLink = ""
          }
          event.text = event.textUrl
          delete event.textUrl
          delete event.hasGalery
        }
        console.log(events);
        console.log(editedTexts);
        try {
          $http({
              method: "POST",
              url: "saveTimeline.php?ajax",
              data: {
                "timeline": events,
                "editedTexts": editedTexts,
                "savefile": ""
              }
            })
            .then(function successCallback(response) {
              console.log(response);
              if (response.data.success) {
                $scope.saveError = false;
                $scope.message = "Saved"
              } else {
                $scope.saveError = true;
                $scope.message = "Error: " + response.data.error
              }
            }, function errorCallback(response) {
              $scope.saveError = true;
              $scope.message = "Error: " + response
            });

        } catch (e) {
          $scope.saveError = true;
          $scope.message = "Error: " + e
        }

      }


    }]);

})();
