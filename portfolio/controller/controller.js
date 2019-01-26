var app = angular.module("dangerApp", ["ngRoute", "ngAnimate"]);

app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : "page/main.html"
        })
        .when("/project", {
            templateUrl : "page/project.html",
            controller : "projectController"
        })
        .when("/contact", {
            templateUrl : "page/contact.html",
            controller : "contactController"
        })
        .otherwise({
            redirectTo: '/'
        });
});


app.controller("projectController", function ($scope) {
    $scope.msg = "PROGETTI";
});
app.controller("contactController", function ($scope) {
    $scope.msg = "CONTATTI";
});