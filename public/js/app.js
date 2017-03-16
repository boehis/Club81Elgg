(function() {
    var app = angular.module("myApp", ["ngRoute"]);
    app.config(function($routeProvider, $locationProvider) {
        $routeProvider
            .when("/", {
                templateUrl: "templates/main.htm"
            })
            .when("/program", {
                templateUrl: "templates/program.htm"
            })
            .when("/join", {
                templateUrl: "templates/join.htm"
            })
            .when("/people", {
                templateUrl: "templates/people.htm"
            })
            .otherwise({redirectTo : '/'});
    })

})();
