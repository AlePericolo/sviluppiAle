var app = angular.module("dangerApp", ["ngRoute", "ngAnimate", "ngMaterial", "ngMessages"]);

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



app.controller("contactController",  ['$scope', '$http', function ($scope, $http) {
    $scope.email = {
        email: '',
        subject: '',
        message: ''
    };
    $scope.sendEmail = function () {
        $http({
            method: 'POST',
            url: 'http://alessandropericolo14.altervista.org/page/contact.php',
            data: $scope.email,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        }).then(function(data){
            if(data.statusText === "OK"){
                Swal.fire('Your email was sent successfully!', 'La sua email è stata inviata correttamente.', 'success')
            }else{
                Swal.fire('Error, email not sent!', 'Errore, la sua email non è stata inviata.', 'error')
            }
        });
    };
}]);