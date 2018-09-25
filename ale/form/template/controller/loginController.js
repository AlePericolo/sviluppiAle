/**
 * Created by alessandro on 19/06/17.
 */

var app = angular.module("ngApp", ["ngAnimate"]);

app.controller("loginController", ["$scope", "$http", "$filter", function ($scope, $http, $filter) {

    var url = window.location.href;
    var params = decodeUrl(url);
    var handler = url.split("login.php");

    $scope.login = {'username': 'ale', 'password': 'ale'};

    $scope.abilitaLogin = false;

    $scope.$watchGroup(["login.username","login.password"],function(){

        if($scope.login) {
            if(
                $scope.login.username != null && $scope.login.username != '' && $scope.login.username.length > 0 &&
                $scope.login.password != null && $scope.login.password != '' && $scope.login.password.length > 0
            ){
                $scope.abilitaLogin = true;
            }else{
                $scope.abilitaLogin = false;
            }
        }
    });

    $scope.effettuaLogin = function () {

        $http.post(handler[0] + "template/controller/loginHandler.php",
            {'function': 'effettuaLogin', 'login': $scope.login}
        ).then(function (data, status, headers, config) {
            //console.log(data.data);
            if(data.data){
                window.location.href = "home.php";
            }else{
                swal("Login Fallito", "", "error");
            }
        });

    };

}]);

