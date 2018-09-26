
ngApp.controller('profiloController', ['$scope', '$http', function ($scope, $http) {

    $scope.params = decodeUrl(window.location.href);

    $scope.caricamentoCompletato = false;
    $scope.showModificaDati = false;

    //caricaDati
    $scope.init = function(){
        $scope.caricaDati();
    };

    /*============================================= CARICO DATI PAGINA ===============================================*/

    $scope.caricaDati = function(){
        $http.post($scope.params['form'] + '/profilo/controller/profiloHandler.php',
            {'function': 'getDatiPagina'}
        ).then(function (data) {
            console.log(data.data);

            $scope.utente = data.data.utente;

            $scope.caricamentoCompletato = true;
        });
    };

    $scope.modificaDatiProfilo = function () {

        $scope.showModificaDati = true;
    };

    $scope.chiudiModifica = function () {

        $scope.showModificaDati = false;
    };

    $scope.salvaDatiUtente = function () {

        console.log($scope.utente);
    };



}]); //CLOSE APP