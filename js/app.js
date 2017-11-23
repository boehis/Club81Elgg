"use strict";

(function() {

  angular.module("myApp", ["ngRoute"])

    .config(['$routeProvider', function($routeProvider) {
      $routeProvider
        .when("/", {
          templateUrl: "templates/main.htm",
          controller: 'TimelineController'
        })
        .when("/program", {
          templateUrl: "templates/program.htm",
          controller: 'ProgramController'
        })
        .when("/join", {
          templateUrl: "templates/join.htm",
          controller: 'PageController'
        })
        .when("/people", {
          templateUrl: "templates/people.htm",
          controller: 'PageController'
        })
        .otherwise({
          redirectTo: '/'
        });
    }])
    .controller('PageController', ['$scope', '$route', '$location', function($scope, $route, $location) {
      switchPage($location);
    }])
    .controller('TimelineController', ['$scope', '$route', '$location', '$http', function($scope, $route, $location, $http) {
      switchPage($location);

      $http({
        method: 'GET',
        url: '../admin/timeline.json'
      }).then(function successCallback(response) {
        var monthMap = ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sept", "Okt", "Nov", "Dez"]

        $scope.events = []

        for (var i = 0; i < response.data.length; i++) {
          var data = response.data[i]
          var date = new Date(data.date)
          var event = {}
          event.day = date.getUTCDate()
          event.month = monthMap[date.getUTCMonth()]
          event.title = data.title
          event.text = "Loading..."
          event.hasGalery = data.galeryLink != "" && data.thumbnail != ""
          event.galeryLink = data.galeryLink
          event.thumbnail = data.thumbnail

          function loadText(event) {
            $http({
              method: 'GET',
              url: '../admin/timeline/'+data.text
            }).then(function successCallback(response) {
              event.text = response.data
            }, function errorCallback(response) {
              event.text = response.statusText;
            });
          }
          loadText(event)

          $scope.events.push(event)
        }
      }, function errorCallback(response) {
        $scope.error = true;
      });

    }])
    .controller('ProgramController', ['$scope', '$route', '$location', '$http', function($scope, $route, $location, $http) {
      switchPage($location);

      $http({
        method: 'GET',
        url: '../admin/program.json'
      }).then(function successCallback(response) {

        var colors = {
          "highlight": "bg-success",
          "compulsory": "bg-primary",
          "public": "bg-info",
          "private": "bg-danger",
          "none": ""
        };
        var category = {
          "highlight": "Highlight",
          "compulsory": "Obligatorisch",
          "public": "Öffentlich",
          "private": "Privat",
          "none": "Mitglieder"
        };

        for (var i = 0; i < response.data.length; i++) {
          var item = response.data[i];
          item.date = new Date(item.date);
          item.cat = category[item.tag];
          item.tag = colors[item.tag];
        }

        var sorted = response.data.sort(function(a, b) {
          if (a.date < b.date)
            return -1;
          if (a.date > b.date)
            return 1;
          return 0;
        });


        var now = new Date();
        var date = new Date(moment(now).format('YYYY-MM-DD'));

        console.log(date);

        var program = splitYears(sorted, date);

        var keys = Object.keys(program);
        var currentYearIndex = keys.indexOf(date.getUTCFullYear().toString());

        var programFuture = {};

        var currentYear = splitPastElements(program[date.getUTCFullYear()], date);
        if (currentYear[1].length > 0) {
          programFuture[date.getUTCFullYear()] = currentYear[1];
        }
        for (var i = currentYearIndex + 1; i < keys.length; i++) {
          programFuture[keys[i]] = program[keys[i]]
        }

        var showPast = false;
        $scope.pastElements = function() {
          showPast = !showPast;
          if (showPast) {
            $scope.program = program;
            $scope.link = "Vergangene Elemente ausblenden";
          } else {
            $scope.program = programFuture;
            $scope.link = "Vergangene Elemente anzeigen";
          }
        };

        $scope.link = "Vergangene Elemente anzeigen";
        $scope.program = programFuture;
      }, function errorCallback(response) {
        $scope.error = response.statusText;
        $scope.link = "";
        $scope.program = {};
      });
    }]);

  function switchPage($location) {
    for (var i = 0; i < $(".link a").length; i++) {
      var link = $(".link a")[i];
      if (link.getAttribute("href") == ("#" + $location.$$path)) {
        link.parentElement.classList.add("active")
      } else {
        link.parentElement.classList.remove("active");
        link.parentElement.classList.remove("active")
      }
    }
  }

  function splitYears(array) {
    var res = {};
    var currentYear = array[0].date.getUTCFullYear();
    var currentArray = [];


    for (var i = 0; i < array.length; i++) {
      var item = array[i];
      if (item.date.getFullYear() == currentYear) {
        currentArray.push(item)
      } else {
        res[currentYear] = currentArray;
        currentYear = item.date.getUTCFullYear();
        currentArray = [];
        currentArray.push(item)
      }
    }
    res[currentYear] = currentArray;

    return res;

  }

  function splitPastElements(array, date) {
    if (!array) {
      return [
        [],
        []
      ]
    }
    if (array[0].date >= date) {
      return [
        [], array
      ]
    } else {
      var i;
      for (i = 1; i < array.length; i++) {
        if (array[i].date >= date) {
          break;
        }
      }
      if (i == array.length) {
        return [array, []]
      } else {
        return [array.slice(0, i), array.slice(i)];
      }
    }

  }


})();
