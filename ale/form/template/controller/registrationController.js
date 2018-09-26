var app = angular.module("ngApp", ["ngAnimate", "angularjs-gauge"]);

app.controller("registrationController", ["$scope", "$http", "$filter", function ($scope, $http, $filter) {

    var url = window.location.href;
    var handler = url.split("registration.php");

    $scope.registration = {'username': '', 'password': '', 'repeatpassword': ''};

    $scope.abilitaSalvataggio = false;
    $scope.messagePassword = false;

    $scope.$watchGroup(["registration.username","registration.password", "registration.repeatpassword"],function(){

        if($scope.registration) {
            if(
                $scope.registration.username != null && $scope.registration.username != '' && $scope.registration.username.length > 0 &&
                checkPassword($scope.registration.password) > 59 &&
                $scope.registration.password == $scope.registration.repeatpassword
            ){
                $scope.abilitaSalvataggio = true;
            }else{
                $scope.abilitaSalvataggio = false;
            }
        }
    });

    $scope.$watchGroup(["registration.password", "registration.repeatpassword"],function(){

        if($scope.registration) {
            if(
                $scope.registration.password != '' && $scope.registration.repeatpassword != '' && $scope.registration.password != $scope.registration.repeatpassword
            ){
                $scope.messagePassword = true;
            }else{
                $scope.messagePassword = false;
            }
        }
    });

    $scope.$watch('registration.password', function() {
        $scope.livelloSicurezza = checkPassword($scope.registration.password);
    });

    $scope.thresholdSecurity = {
        '0': { color: 'red' },
        '20': {color: 'orange' },
        '40': {color: 'yellow' },
        '60': {color: 'lightgreen'},
        '80': {color: 'green'}
    };

    $scope.registraUtente = function () {

        $http.post(handler[0] + "template/controller/registrationHandler.php",
            {'function': 'registraUtente', 'registration': $scope.registration}
        ).then(function (data) {
            console.log(data.data);
            if(data.data.status == "KO"){
                swal("Errore!", data.data.message, "error");
            }
            if(data.data.status == "OK"){

                swal({
                        title: data.data.message,
                        text: "",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "OK",
                        closeOnConfirm: true
                    },
                    function(){
                        window.location.href = "login.php";
                    }
                );
            }
        });
    };

    $scope.goToLogin = function () {
        window.location.href = "login.php";
    };

}]);