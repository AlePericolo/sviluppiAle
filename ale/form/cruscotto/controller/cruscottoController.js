
ngApp.controller('cruscottoController', ['$scope', '$http', function ($scope, $http) {


    $scope.params = decodeUrl(window.location.href);

    $scope.caricamentoCompletato = false;

    $scope.init = function(){
        $scope.caricaDati();
    };

    /*========================================== CARICO DATI PAGINA ==================================================*/

    $scope.caricaDati = function() {

        $http.post($scope.params['form'] + '/cruscotto/controller/cruscottoHandler.php',
            {'function': 'getDatiPagina'}
        ).then(function (data) {
            console.log(data.data);

            $scope.elencoPost = data.data.elencoPost;

        }).then(function () {
            $scope.caricamentoCompletato = true;
        });

    };


}]);
