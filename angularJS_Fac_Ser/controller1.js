var app = angular.module('myApp', []);

app.controller('controller1', ['$scope', 'sommaFactory', 'sommaService', function ($scope, sommaFactory, sommaService) {

    $scope.sumFactory = sommaFactory(1,2);
    $scope.sumService = sommaService.somma(1,2);
}]);

