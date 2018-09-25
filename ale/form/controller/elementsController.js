
var app = angular.module("ngApp", []);

app.controller("elementsController",['$scope','$http', function($scope, $http){

    var url = decodeUrl(window.location.href);

    $scope.nuovoElemento = false;

    //caricaDati
    onload = function () {
        $scope.caricaDati();
    };

    /*=============================================== CARICO DATI PAGINA ===============================================*/

    $scope.caricaDati = function(){
        $http.post(url['percorso'] + '/form/controller/elementsHandler.php',
            {'function': 'getDatiPagina'}
        ).then(function (data, status, headers, config) {
            console.log(data.data);
            $scope.elements = data.data.elements;

        });
    };

    $scope.nuovo = function () {
        $http.post(url['percorso'] + '/form/controller/elementsHandler.php',
            {'function': 'nuovoElemento'}
        ).then(function (data, status, headers, config) {
            console.log(data.data);
            $scope.newElement = data.data.newElement;
            $scope.nuovoElemento = true;
        });
    };

    $scope.inserisci = function () {
        $http.post(url['percorso'] + '/form/controller/elementsHandler.php',
            {'function': 'inserisciElemento', 'elemento': $scope.newElement}
        ).then(function (data, status, headers, config) {
            console.log(data.data);
            if(data.data.response == 'OK'){
                swal({
                        title: "Elemento inserito",
                        text: "",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "OK",
                        closeOnConfirm: true
                    },
                    function(){
                        window.location.reload();
                    });
            }else{
                swal("Errore!", data.data.message, "error");
            }
        });
    };


}]); //CLOSE APP