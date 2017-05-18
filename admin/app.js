"use strict";

(function () {

    angular.module("admin", [])
        .controller('controller', function ($scope, $http) {
            $http({
                method: 'GET',
                url: 'program.json'
            }).then(function successCallback(response) {

                $scope.colors = {
                    "highlight": "bg-success",
                    "compulsory": "bg-primary",
                    "public": "bg-info",
                    "private": "bg-danger",
                    "none": ""
                };
                var program = response.data.sort(function (a, b) {
                    if (a.date < b.date)
                        return -1;
                    if (a.date > b.date)
                        return 1;
                    return 0;
                });

                for (var i = 0; i < program.length; i++) {
                    program[i].date = new Date(program[i].date);
                }

                $scope.program = program;
                $scope.parseError = "";

                $scope.save = function () {
                    try {
                        $http({
                            method: "post",
                            url: "save.php?ajax",
                            data: {
                                "program": $scope.program,
                                "savefile": ""
                            }
                        })
                            .then(function successCallback(response) {
                                if(response.data.success){
                                    $scope.error = false;
                                    $scope.message = "Saved"
                                }else {
                                    $scope.error = true;
                                    $scope.message = "Error: " + response.data.error
                                }
                            }, function errorCallback(response) {
                                $scope.error = true;
                                $scope.message = "Error: " + response
                            });

                    } catch (e) {
                        $scope.error = true;
                        $scope.message = "Error: " + e
                    }

                }

            }, function errorCallback(response) {
                $scope.error = true;
                $scope.message = "Error: " + response;
                $scope.program = {};
                $scope.colors = {};
            });
        });

})();
