
ngApp.controller('anagraficaController', ['$scope', function ($scope) {

    $scope.params = decodeUrlTest(window.location.href);

    $scope.titoloAnagrafica = "Anagrafica";

    $scope.goToContratto = function (id) {

        window.location.href = $scope.params['baseUrl'] + "?pagina=contratto&id=" + id;

    }

}]);