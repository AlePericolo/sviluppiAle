
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

    $scope.valutaPost = function (valutazione) {
        //console.log(valutazione);

        $http.post($scope.params['form'] + '/cruscotto/controller/cruscottoHandler.php',
            {'function': 'valutaPost', 'valutazione': valutazione}
        ).then(function (data) {
            console.log(data.data);
            if(data.data.response == "OK"){
                window.location.reload();
            }else{
                swal("Errore", data.data.message, "error");
            }
        })
    };

    $scope.thresholdVote = {
        '0': {color: 'red' },
        '2': {color: 'orange' },
        '4': {color: 'green' }
    };

}]);
