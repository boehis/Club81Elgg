(function() {

    var app = angular.module("myApp", ["ngRoute"])

        .controller('PageController', function($scope, $route, $location) {
            for (link of $(".link a")) {
                if (link.getAttribute("href") == ("#" + $location.$$path)) {
                    link.parentElement.classList.add("active")
                }else {
                  link.parentElement.classList.remove("active")
                }
            }
        })

        .config(function($routeProvider, $locationProvider) {
            $routeProvider
                .when("/", {
                    templateUrl: "templates/main.htm",
                    controller: 'PageController'
                })
                .when("/program", {
                    templateUrl: "templates/program.htm",
                    controller: 'PageController'
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
        })

})();
