
ngApp.controller('cruscottoController', ['$scope', '$http', function ($scope, $http) {


    /*-------------------------------------------------VARIABILI------------------------------------------------------*/

    $scope.caricamentoCompletato = false;
    $scope.showArticolo = false;

    /*-------------------------------------------------CARICA DATI----------------------------------------------------*/

    $scope.init = function(){
        $scope.caricaDati();
    };

    $scope.caricaDati = function() {

        $scope.caricamentoCompletato = true;

    };


}]);
