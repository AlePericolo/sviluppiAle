var app = angular.module("dangerApp", ["ngRoute", "ngAnimate", "ngMaterial", "ngMessages"]);

app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : "page/main.html"
        })
        .when("/projects", {
            templateUrl : "page/projects.html",
            controller : "projectsController"
        })
        .when("/contact", {
            templateUrl : "page/contact.html",
            controller : "contactController"
        })
        .otherwise({
            redirectTo: '/'
        });
});


app.controller("projectsController", function ($scope) {

    $scope.projects = [
                        {title: 'SVILUPPIALE', description: 'Random projects developed for test.', link: 'sviluppiAle'},
                        {title: 'PYTHONALE', description: 'Python local test.', link: 'pythonAle'},
                        {title: 'PERILBOT', description: 'Telegram Bot. Developed for fun.', link: 'perilBot'},
                        {title: 'PYTHONMODELER', description: 'Write PHP classes from MySQL db.', link: 'pythonModeler'},
                        {title: 'SNAKE', description: 'The classic snake game.', link: 'snake'},
                        {title: 'PERILNETWORK', description: 'Mini social network.', link: 'perilNetwork'}
                      ];
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